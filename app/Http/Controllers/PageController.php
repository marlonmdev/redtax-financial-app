<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Note;
use App\Models\Task;
use App\Models\Client;
use App\Models\Service;
use App\Models\Document;
use App\Models\BlockTime;
use App\Enums\ContactType;
use App\Enums\ServiceType;
use App\Models\AccessRequest;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AppointmentSchedule;
use Illuminate\Support\Facades\Cache;
use Label84\HoursHelper\Facades\HoursHelper;

class PageController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function document_upload()
    {
        $phone = ContactType::PHONE->value;
        $email = ContactType::EMAIL->value;
        $serviceTypes = ServiceType::cases();
        return view('document-upload', compact('phone', 'email', 'serviceTypes'));
    }

    public function contact()
    {
        $serviceTypes = ServiceType::cases();
        return view('contact', compact('serviceTypes'));
    }

    public function appointment()
    {
        $services = Service::all();
        $formattedServices = $services->map(function ($service) {
            $hours = floor($service->duration / 60);
            $minutes = $service->duration % 60;

            $formattedDuration = '';
            if ($hours > 0) {
                $formattedDuration .= $hours . ' ' . Str::plural('hour', $hours) . ' ';
            }
            if ($minutes > 0) {
                $formattedDuration .= $minutes . ' ' . Str::plural('minute', $minutes);
            }

            $service->formatted_duration = trim($formattedDuration);
            return $service;
        });

        return view('appointment',  ['services' => $formattedServices]);
    }

    public function showCalendar(Service $service, Request $request)
    {
        $hours = floor($service->duration / 60);
        $minutes = $service->duration % 60;

        $formattedDuration = '';
        if ($hours > 0) {
            $formattedDuration .= $hours . ' ' . Str::plural('hour', $hours) . ' ';
        }
        if ($minutes > 0) {
            $formattedDuration .= $minutes . ' ' . Str::plural('minute', $minutes);
        }

        $service->formatted_duration = trim($formattedDuration);

        return view('appointment-calendar', compact('service'));
    }

    public function getAvailableDays()
    {
        // Get available days from the appointment_schedules table
        $availableDays = DB::table('appointment_schedules')
            ->whereIn('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
            ->distinct()
            ->get(['day', 'start_time', 'end_time']);

        // Get block times from the block_times table
        $blockTimes = DB::table('block_times')->get();

        // Initialize an array to store unavailable dates
        $unavailableDates = [];

        foreach ($blockTimes as $block) {
            $blockDate = Carbon::parse($block->block_date);
            $blockStart = Carbon::parse($block->block_start_time);
            $blockEnd = Carbon::parse($block->block_end_time);

            // Find matching appointment schedules for the blocked day
            foreach ($availableDays as $schedule) {
                $scheduleDay = Carbon::parse($block->block_date)->format('l');

                // If the day matches the schedule day
                if ($scheduleDay === $schedule->day) {
                    // Convert schedule times to Carbon instances
                    $scheduleStart = Carbon::parse($schedule->start_time);
                    $scheduleEnd = Carbon::parse($schedule->end_time);

                    // Disable the date only if block covers the entire schedule
                    if ($blockStart <= $scheduleStart && $blockEnd >= $scheduleEnd) {
                        $unavailableDates[] = $blockDate->toDateString(); // Disable this date
                    }
                }
            }
        }

        return response()->json([
            'availableDays' => $availableDays->pluck('day'),
            'unavailableDates' => $unavailableDates
        ]);
    }

    public function getTimeSlots(Request $request)
    {
        $date = $request->input('date');
        $serviceId = $request->input('serviceId');

        $dayOfWeek = Carbon::parse($date)->format('l');

        $service = Service::findOrFail($serviceId);
        $schedule = AppointmentSchedule::where('day', $dayOfWeek)->first();

        if (!$schedule) {
            return response()->json([]);
        }

        // Retrieve all block times and existing appointments for the selected date
        $blockTimes = BlockTime::where('block_date', $date)->get();
        $existingAppointments = Appointment::where('date', $date)->get(['start_time', 'end_time']);

        // Generate time slots
        $timeSlots = $this->generateTimeSlots($schedule, $service->duration, $blockTimes, $existingAppointments);

        return response()->json([
            'time_slots' => $timeSlots,
            'service_duration' => $service->duration,
            'break_start_time' => $schedule->break_start_time,
            'break_end_time' => $schedule->break_end_time,
        ]);
    }

    private function generateTimeSlots($schedule, $serviceDuration, $blockTimes, $existingAppointments)
    {
        $timeSlots = [];

        $isBlocked = function ($slotTime) use ($blockTimes) {
            $slotTime = Carbon::parse($slotTime);
            foreach ($blockTimes as $blockTime) {
                $blockStart = Carbon::parse($blockTime->block_start_time);
                $blockEnd = Carbon::parse($blockTime->block_end_time);
                if ($slotTime->between($blockStart, $blockEnd->subMinute())) {
                    return true;
                }
            }
            return false;
        };

        $isBooked = function ($slotTime) use ($existingAppointments, $serviceDuration) {
            $slotTime = Carbon::parse($slotTime);
            foreach ($existingAppointments as $appointment) {
                $appointmentStart = Carbon::parse($appointment->start_time);
                $appointmentEnd = Carbon::parse($appointment->end_time)->subMinutes($serviceDuration);
                if ($slotTime->between($appointmentStart, $appointmentEnd)) {
                    return true;
                }
            }
            return false;
        };

        $isBeyondScheduleEnd = function ($slotTime, $scheduleEndTime) use ($serviceDuration) {
            $slotEndTime = Carbon::parse($slotTime)->addMinutes($serviceDuration);
            return $slotEndTime > Carbon::parse($scheduleEndTime);
        };

        $beforeBreakSlots = $this->createSlots(
            $schedule->start_time,
            $schedule->break_start_time ?? $schedule->end_time,
            $serviceDuration,
            $isBlocked,
            $isBooked,
            $isBeyondScheduleEnd,
            $schedule->end_time
        );
        $timeSlots = array_merge($timeSlots, $beforeBreakSlots);

        if ($schedule->break_start_time && $schedule->break_end_time) {
            $afterBreakSlots = $this->createSlots(
                $schedule->break_end_time,
                $schedule->end_time,
                $serviceDuration,
                $isBlocked,
                $isBooked,
                $isBeyondScheduleEnd,
                $schedule->end_time
            );
            $timeSlots = array_merge($timeSlots, $afterBreakSlots);
        }

        return $timeSlots;
    }

    private function createSlots($startTime, $endTime, $serviceDuration, $isBlocked, $isBooked, $isBeyondScheduleEnd, $scheduleEndTime)
    {
        $slots = [];
        $currentSlot = Carbon::parse($startTime);

        while ($currentSlot->addMinutes($serviceDuration)->lessThanOrEqualTo(Carbon::parse($endTime))) {
            $slotTime = $currentSlot->copy()->format('H:i');
            if (!$isBlocked($slotTime) && !$isBooked($slotTime) && !$isBeyondScheduleEnd($slotTime, $scheduleEndTime)) {
                $slots[] = $slotTime;
            }
        }

        return $slots;
    }

    public function tax_services()
    {
        return view('tax-services');
    }

    public function audit_representation()
    {
        return view('audit-representation');
    }

    public function business_consulting()
    {
        return view('business-consulting');
    }

    public function bookkeeping_and_payroll()
    {
        return view('bookkeeping-and-payroll');
    }

    public function tax_planning()
    {
        return view('tax-planning');
    }

    public function life_insurance()
    {
        return view('life-insurance');
    }

    public function health_insurance()
    {
        return view('health-insurance');
    }

    public function dashboard()
    {
        $count = Cache::remember('model_counts', 10, function () {

            if (auth()->user()->role->role_name !== 'Client' && auth()->user()->role->role_name !== 'Staff') {
                // Count all records for Admin, Manager etc.
                return [
                    'clients' => Client::select(DB::raw('COUNT(DISTINCT CONCAT(name, email)) as count'))->value('count'),
                    'accessRequests' => AccessRequest::count(),
                    'documents' => Document::count(),
                    'tasks' => Task::count(),
                    'taskNotes' => Note::where('message_to', auth()->id())->count(),
                ];
            } else {
                // Count only records where the user_id is equal to the authenticated user (for Clients and Staff)
                return [
                    'clients' => Client::select(DB::raw('COUNT(DISTINCT CONCAT(name, email)) as count'))->value('count'),
                    'accessRequests' => AccessRequest::count(),
                    'documents' => Document::where('uploaded_by', auth()->user()->name)->count(),
                    'tasks' => Task::where('user_id', auth()->id())->count(),
                    'taskNotes' => Note::where('message_to', auth()->id())->count(),
                ];
            }
        });

        $todaysClients = Client::whereDate('added_on', Carbon::today())->get();

        $recentClients = Cache::remember('recent_clients', 30, function () {
            return $this->getRecentClients(7);
        });

        $recentDocuments = Cache::remember('recent_documents', 30, function () {
            return $this->getRecentDocuments(7);
        });

        $weeklyAppointments = Cache::remember('this_week_appointments', 30, function () {
            return $this->getThisWeekAppointments();
        });

        return view('dashboard.index', compact('count', 'recentClients', 'recentDocuments', 'weeklyAppointments'));
    }

    public function getRecentClients($days = 30)
    {
        return Client::where('added_on', '>=', Carbon::now()->subDays($days))
            ->orderBy('added_on', 'desc')
            ->limit(5)
            ->get();
    }


    public function getRecentDocuments($days = 30)
    {
        return Document::where('upload_date', '>=', Carbon::now()->subDays($days))
            ->with('client')
            ->orderBy('upload_date', 'desc')
            ->limit(5)
            ->get();
    }

    public function getThisWeekAppointments()
    {
        // Get the current date and time
        $currentDateTime = Carbon::now();

        // Get the start and end dates of the current week
        $startOfWeek = $currentDateTime->copy()->startOfWeek();  // Monday
        $endOfWeek = $currentDateTime->copy()->endOfWeek();      // Sunday

        // Retrieve appointments for this week with a status of 'Pending'
        $appointments = Appointment::with('service')
            ->where('status', '=', 'Pending')
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)  // Limit to 10 appointments
            ->get();

        return $appointments;
    }
}
