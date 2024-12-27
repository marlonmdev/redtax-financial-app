<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\AuditLog;
use App\Models\BlockTime;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClientService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\AppointmentSchedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentRescheduleEmail;
use App\Mail\AppointmentCancellationEmail;
use App\Mail\AppointmentConfirmationEmail;
use Label84\HoursHelper\Facades\HoursHelper;

class AppointmentController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'date'); // Default sort field is 'date'
        $sortDirection = request('sort_direction', 'asc'); // Default sort direction is 'asc'
        $schedule = 'this-week';

        // Validate sortDirection
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default to 'asc' if invalid
        }

        // Get the current week's start and end dates
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Perform the search with Laravel Scout to get matching IDs
        $searchResults = Appointment::search($search)->get();

        // Apply additional Eloquent constraints (status and date range)
        $query = Appointment::whereIn('id', $searchResults->pluck('id')) // Filter by the search result IDs
            ->where('status', 'Pending')
            ->whereBetween('date', [$startOfWeek, $endOfWeek]);

        // Apply sorting based on sortField and sortDirection
        if ($sortField === 'start_time') {
            // Special handling for start_time to sort by date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', $sortDirection); // Use user-defined sort direction
        } else if (in_array($sortField, ['id', 'name'])) {
            // Sort by id or name directly
            $query = $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'date') {
            // Special handling for date sorting
            $query = $query->orderBy('date', $sortDirection); // Use user-defined sort direction
        } else {
            // Default sorting: date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', 'asc'); // Always ascending by default
        }

        // Get all results matching the search query
        $allResults = $query->get();

        // Paginate the results
        $currentPage = $page;
        $results = $allResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $appointments = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $allResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );
        $appointments->load('service');

        return view('appointments.index', [
            'schedule' => $schedule,
            'appointments' => $appointments,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function previous()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'date');
        $sortDirection = request('sort_direction', 'asc');
        $schedule = 'previous';

        // Validate sortDirection
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default to 'asc' if invalid
        }

        // Get the start of the current week (Monday)
        $startOfCurrentWeek = Carbon::now()->startOfWeek();

        $searchResults = Appointment::search($search)->get();

        $query = Appointment::whereIn('id', $searchResults->pluck('id'))
            ->where('status', 'Pending')
            ->where('date', '<', $startOfCurrentWeek->toDateString()); // Only dates before the current week

        // Apply sorting based on sortField and sortDirection
        if ($sortField === 'start_time') {
            // Special handling for start_time to sort by date and start_time
            $query = $query->orderBy('date', 'asc')
                ->orderBy('start_time', $sortDirection);
        } else if (in_array($sortField, ['id', 'name'])) {
            // Sort by id or name directly
            $query = $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'date') {
            // Special handling for date sorting
            $query = $query->orderBy('date', $sortDirection);
        } else {
            // Default sorting: date and start_time
            $query = $query->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc');
        }

        $allResults = $query->get();

        // Paginate the results
        $currentPage = $page;
        $results = $allResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $appointments = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $allResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );
        $appointments->load('service');

        return view('appointments.previous', [
            'schedule' => $schedule,
            'appointments' => $appointments,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }


    public function upcoming()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'date'); // Default sort field is 'date'
        $sortDirection = request('sort_direction', 'asc'); // Default sort direction is 'asc'
        $schedule = 'upcoming';

        // Validate sortDirection
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default to 'asc' if invalid
        }

        // Get the end date of the current week
        $endOfWeek = Carbon::now()->endOfWeek();

        // Perform the search with Laravel Scout to get matching IDs
        $searchResults = Appointment::search($search)->get();

        // Apply additional Eloquent constraints: status and date after current week
        $query = Appointment::whereIn('id', $searchResults->pluck('id')) // Filter by search result IDs
            ->where('status', 'Pending')
            ->where('date', '>', $endOfWeek);

        // Apply sorting based on sortField and sortDirection
        if ($sortField === 'start_time') {
            $query = $query->orderBy('date', 'asc')
                ->orderBy('start_time', $sortDirection);
        } else if (in_array($sortField, ['id', 'name'])) {
            $query = $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'date') {
            $query = $query->orderBy('date', $sortDirection);
        } else {
            $query = $query->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc');
        }

        // Get paginated results
        $appointments = $query->paginate($perPage, ['*'], 'page', $page);

        $appointments->load('service');

        return view('appointments.upcoming', [
            'schedule' => $schedule,
            'appointments' => $appointments,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function completed()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'date'); // Default sort field is 'date'
        $sortDirection = request('sort_direction', 'asc'); // Default sort direction is 'asc'
        $schedule = 'completed';

        // Validate sortDirection
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default to 'asc' if invalid
        }

        // Perform the search with Laravel Scout
        $query = Appointment::search($search)->where('status', 'Completed');

        // Apply sorting based on sortField and sortDirection
        if ($sortField === 'start_time') {
            // Special handling for start_time to sort by date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', $sortDirection); // Use user-defined sort direction
        } else if (in_array($sortField, ['id', 'name'])) {
            // Sort by id or name directly
            $query = $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'date') {
            // Special handling for date sorting
            $query = $query->orderBy('date', $sortDirection); // Use user-defined sort direction
        } else {
            // Default sorting: date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', 'asc'); // Always ascending by default
        }

        // Get all results matching the search query
        $allResults = $query->get();

        // Paginate the results
        $currentPage = $page;
        $results = $allResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $appointments = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $allResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );
        $appointments->load('service');

        return view('appointments.completed', [
            'schedule' => $schedule,
            'appointments' => $appointments,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function clientAppointments()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'date'); // Default sort field is 'date'
        $sortDirection = request('sort_direction', 'asc'); // Default sort direction is 'asc'
        $schedule = 'client-only';

        // Validate sortDirection
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // Default to 'asc' if invalid
        }

        // Perform the search with Laravel Scout
        $query = Appointment::search($search)->where('email', auth()->user()->email);

        // Apply sorting based on sortField and sortDirection
        if ($sortField === 'start_time') {
            // Special handling for start_time to sort by date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', $sortDirection); // Use user-defined sort direction
        } else if (in_array($sortField, ['id', 'name'])) {
            // Sort by id or name directly
            $query = $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'date') {
            // Special handling for date sorting
            $query = $query->orderBy('date', $sortDirection); // Use user-defined sort direction
        } else {
            // Default sorting: date and start_time
            $query = $query->orderBy('date', 'asc') // Always ascending by default
                ->orderBy('start_time', 'asc'); // Always ascending by default
        }

        // Get all results matching the search query
        $allResults = $query->get();

        // Paginate the results
        $currentPage = $page;
        $results = $allResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $appointments = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $allResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );
        $appointments->load('service');

        return view('appointments.index', [
            'schedule' => $schedule,
            'appointments' => $appointments,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'service_id' => ['required'],
                'date' => ['required', 'date'],
                'start_time' => ['required', 'before:end_time'],
                'end_time' => ['required', 'after:start_time'],
                'name' => ['required', 'min:5'],
                'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
                'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
                'location' => ['required', Rule::in(['In-Office', 'Zoom'])],
                'details' => ['nullable'],
                'referred_by' => ['nullable']
            ],
            [
                'service_id.required' => 'Please select a service.',
            ]
        );

        $clientName = ucwords(strip_tags($validated['name']));
        $clientEmail = strip_tags($validated['email']);
        $clientPhone = $validated['phone'];
        $clientReferrer = $validated['referred_by'];
        $details =  $validated['details'];

        $service = Service::find($validated['service_id']);
        $serviceName = $service ? $service->service : null;

        $validated['name'] = $clientName;
        $validated['status'] = 'Pending';

        $created = Appointment::create($validated);

        if (!$created) {
            notify()->error('Failed to Schedule Appointment. Please Try Again.', 'Error');
            return redirect()->route('appointment.calendar');
        }

        $existingClient = DB::table('clients')
            ->whereRaw('LOWER(name) = ?', [strtolower($clientName)])
            ->first();

        if (!$existingClient) {
            $clientId = $this->storeClientDetails($clientName, $clientEmail, $clientPhone, $clientReferrer);
            if ($clientId) {
                $this->storeClientService($clientId, $serviceName, $details);
            }
        } else {
            $clientId = $existingClient->id;
            $this->storeClientService($clientId, $serviceName, $details);
        }

        $details = [
            'name' => $clientName,
            'email' => $clientEmail,
            'serviceName' => $serviceName,
            'location' => $validated['location'],
            'appointmentDate' => date('l - F d, Y', strtotime($validated['date'])),
            'appointmentTime' => date('g:i A', strtotime($validated['start_time'])) . ' - ' . date('g:i A', strtotime($validated['end_time'])),
        ];

        // Send email after
        Mail::to($clientEmail)->send(new AppointmentConfirmationEmail($details));

        notify()->success('Appointment Scheduled Successfully', 'Success');
        return redirect()->route('appointment.calendar');
    }

    public function storeClientDetails($clientName, $clientEmail, $clientPhone, $clientReferrer)
    {
        $existingUserAccount = DB::table('users')
            ->where('email', $clientEmail)
            ->first();

        // Handle user account creation if it doesn't exist            
        $insertedUserId = $existingUserAccount ? $existingUserAccount->id : $this->createClientUserAccount($clientName, $clientEmail);

        $clientId = DB::table('clients')->insertGetId([
            'name' => $clientName,
            'email' => $clientEmail,
            'phone' => $clientPhone,
            'preferred_contact' => 'Phone',
            'referred_by' => $clientReferrer ?? null,
            'user_id' => $insertedUserId,
        ]);

        return $clientId;
    }

    public function storeClientService($clientId, $serviceName, $details)
    {
        $clientService = new ClientService;
        $clientService->client_id = $clientId;
        $clientService->services = $serviceName;
        $clientService->details = $details;
        $clientService->save();
    }

    public function createClientUserAccount($clientName, $clientEmail)
    {
        $user = new User;
        $user->name = $clientName;
        $user->email = $clientEmail;
        $user->password = Hash::make(Str::random(8));
        $user->role_id = Role::where('role_name', 'Client')->value('id');
        $user->has_access = 0;

        if ($user->save()) {
            return $user->id;
        }

        return false;
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('service');
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $services = Service::all();
        $appointment->load('service');
        return view('appointments.edit', compact('appointment', 'services'));
    }

    public function update(Appointment $appointment, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'location' => ['required', Rule::in(['In-Office', 'Zoom'])],
            'details' => ['nullable'],
            'referred_by' => ['nullable'],
            'status' => ['required', Rule::in(['Pending', 'Completed'])],
        ]);

        $validated['name'] = ucwords(strip_tags($validated['name']));
        $updated = $appointment->update($validated);

        if (!$updated) {
            notify()->error('Appointment Update Failed', 'Error');
            return redirect()->route('appointments.edit', ['appointment' => $appointment->id]);
        }

        notify()->success('Appointment Updated Successfully', 'Success');
        return redirect()->route('appointments.edit', ['appointment' => $appointment->id]);
    }


    public function destroy(Appointment $appointment)
    {
        $service = Service::findOrFail($appointment->service_id);
        $appointmentClient = $appointment->name;
        $appointmentEmail = $appointment->email;
        $appointmentService = $service->service;
        $appointmentLocation =  $appointment->location;
        $appointmentDate =  $appointment->date;
        $appointmentStartTime =  $appointment->start_time;
        $appointmentEndTime =  $appointment->end_time;

        if (!$appointment->delete()) {
            notify()->error('Appointment Cancellation Failed', 'Error');
            return redirect()->route('appointments.index');
        }

        $details = [
            'name' => $appointmentClient,
            'email' => $appointmentEmail,
            'serviceName' => $appointmentService,
            'location' => $appointmentLocation,
            'appointmentDate' => date('l - F d, Y', strtotime($appointmentDate)),
            'appointmentTime' => date('g:i A', strtotime($appointmentStartTime)) . ' - ' . date('g:i A', strtotime($appointmentEndTime)),
        ];

        // Send email after
        Mail::to($appointmentEmail)->send(new AppointmentCancellationEmail($details));

        // Save the delete log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Cancelled an appointment for " . $appointmentClient . " scheduled for " .  Carbon::parse($appointmentDate)->format('F j, Y');
        $auditLog->save();

        notify()->success('Appointment Cancelled Successfully', 'Success');
        return back();
    }

    public function reschedule(Appointment $appointment)
    {
        $services = Service::all();
        $appointment->load('service');
        return view('appointments.reschedule', compact('appointment', 'services'));
    }

    function updateSchedule(Appointment $appointment, Request $request)
    {
        $rules = [
            'service_id' => ['required'],
            'date' => ['required', 'date', 'after:today'],
            'location' => ['required', Rule::in(['In-Office', 'Zoom'])],
        ];

        // Add timeslot validation only if the date is changing
        if ($request->input('date') != $appointment->date) {
            $rules['timeslot'] = ['required'];
        }

        $messages = [
            'service_id.required' => 'Please select a service.',
        ];

        $validated = $request->validate($rules, $messages);

        $service = Service::findOrFail($validated['service_id']);

        // Handle timeslot
        if (isset($validated['timeslot'])) {
            $validated['start_time'] = $validated['timeslot'];
            $validated['end_time'] = Carbon::parse($validated['start_time'])->addMinutes($service->duration);
            unset($validated['timeslot']);
        } else {
            // Keep the existing start and end times if timeslot wasn't provided
            $validated['start_time'] = $appointment->start_time;
            $validated['end_time'] = $appointment->end_time;
        }

        $updated = $appointment->update($validated);

        if (!$updated) {
            notify()->error('Appointment Reschedule Failed', 'Error');
            return redirect()->route('appointments.reschedule', ['appointment' => $appointment->id]);
        }

        $details = [
            'name' => $appointment->name,
            'email' => $appointment->email,
            'serviceName' => $service->service,
            'location' => $validated['location'],
            'appointmentDate' => date('l - F d, Y', strtotime($validated['date'])),
            'appointmentTime' => date('g:i A', strtotime($validated['start_time'])) . ' - ' . date('g:i A', strtotime($validated['end_time'])),
        ];

        // Send email after
        Mail::to($appointment->email)->send(new AppointmentRescheduleEmail($details));

        notify()->success('Appointment Rescheduled Successfully', 'Success');
        return redirect()->route('appointments.reschedule', ['appointment' => $appointment->id]);
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
            'timeSlots' => $timeSlots,
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
                if ($slotTime->between($blockStart, $blockEnd->subSecond())) {
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

        while ($currentSlot->addMinutes(0)->lessThan(Carbon::parse($endTime))) {
            $slotTime = $currentSlot->copy()->format('H:i:s');
            if (!$isBlocked($slotTime) && !$isBooked($slotTime) && !$isBeyondScheduleEnd($slotTime, $scheduleEndTime)) {
                $slots[] = [
                    'value' => $slotTime,
                    'label' => Carbon::parse($slotTime)->format('g:i A') . ' - ' . Carbon::parse($slotTime)->addMinutes($serviceDuration)->format('g:i A'),
                ];
            }
            $currentSlot->addMinutes($serviceDuration);
        }

        return $slots;
    }

    // Handing appointments booking inside the Client Dashboard
    public function book()
    {
        $services = Service::all();
        $client = Client::where('email', auth()->user()->email)->first();
        return view('appointments.book', compact('services', 'client'));
    }

    public function storeClientAppointment(Request $request)
    {
        $validated = $request->validate(
            [
                'service_id' => ['required'],
                'date' => ['required', 'date'],
                'timeslot' => ['required'],
                'name' => ['required', 'min:5'],
                'email' => ['required', 'email'],
                'phone' => ['nullable'],
                'location' => ['required', Rule::in(['In-Office', 'Zoom'])],
                'details' => ['nullable'],
                'referred_by' => ['nullable'],
            ],
            [
                'service_id.required' => 'Please select a service.',
            ]
        );

        $clientName = $validated['name'];
        $clientEmail = strip_tags($validated['email']);
        $details =  $validated['details'];

        $service = Service::findOrFail($validated['service_id']);
        $serviceName = $service ? $service->service : null;

        $validated['name'] = $validated['name'];
        $validated['status'] = 'Pending';

        // Handle timeslot
        if (isset($validated['timeslot'])) {
            $validated['start_time'] = $validated['timeslot'];
            $validated['end_time'] = Carbon::parse($validated['start_time'])->addMinutes($service->duration);
            unset($validated['timeslot']);
        }

        $created = Appointment::create($validated);

        if (!$created) {
            notify()->error('Failed to Schedule Appointment. Please Try Again.', 'Error');
            return redirect()->route('appointment.calendar');
        }

        $client = Client::where('email', auth()->user()->email)->first();

        $this->storeClientService($client->id, $serviceName, $details);

        $details = [
            'name' => $clientName,
            'email' => $clientEmail,
            'serviceName' => $serviceName,
            'location' => $validated['location'],
            'appointmentDate' => date('l - F d, Y', strtotime($validated['date'])),
            'appointmentTime' => date('g:i A', strtotime($validated['start_time'])) . ' - ' . date('g:i A', strtotime($validated['end_time'])),
        ];

        // Send email after
        Mail::to($clientEmail)->send(new AppointmentConfirmationEmail($details));

        notify()->success('Appointment Scheduled Successfully', 'Success');
        return redirect()->route('my-appointments');
    }

    public function showClientAppointment(Request $request)
    {
        $appointment = Appointment::findOrFail($request->id);
        $appointment->load('service');
        return view('appointments.show', compact('appointment'));
    }
}
