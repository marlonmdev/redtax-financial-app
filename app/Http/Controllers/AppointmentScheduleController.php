<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AppointmentSchedule;
use App\Models\BlockTime;

class AppointmentScheduleController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'asc');

        // Perform the search with Laravel Scout
        $query = AppointmentSchedule::search($search);

        // Get all results matching the search query
        $allResults = $query->get();

        // Apply sorting based on field type
        $sortedResults = $allResults->sortBy(function ($user) use ($sortField, $sortDirection) {
            $value = $user->{$sortField};

            // Determine if the value is numeric
            if (is_numeric($value)) {
                $value = (int) $value; // Ensure value is treated as integer
            }

            return $value;
        }, SORT_REGULAR, $sortDirection === 'desc');


        // Paginate the sorted results manually
        $currentPage = $page;
        $results = $sortedResults->forPage($currentPage, $perPage);

        // Create a LengthAwarePaginator instance
        $schedules = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );


        return view('appointment_schedules.index', [
            'schedules' => $schedules,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        $blockTime = BlockTime::first();
        return view('appointment_schedules.create', compact('blockTime'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'days' => ['required', 'array'],
            'days.*' => ['string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'start_time' => [
                'required',
                Rule::when($request->filled('break_start_time'), ['before:break_start_time'], ['before:end_time'])
            ],
            'break_start_time' => ['nullable', 'after:start_time', 'required_with:break_end_time'],
            'break_end_time' => ['nullable', 'after:break_start_time', 'required_with:break_start_time'],
            'end_time' => [
                'required',
                Rule::when($request->filled('break_end_time'), ['after:break_end_time'], ['after:start_time'])
            ],
        ], [
            'days.required' => 'Please checked at least one day.',
            'start_time.before' => 'The start time must be before break start time or end time.',
            'end_time.after' => 'The end time must be after break end time or start time.'
        ]);

        foreach ($request->days as $day) {
            $schedule = AppointmentSchedule::where('day', $day)->first();

            if (!$schedule) {
                AppointmentSchedule::create([
                    'day' => $day,
                    'start_time' => $validated['start_time'],
                    'break_start_time' => $validated['break_start_time'] ?? null,
                    'break_end_time' => $validated['break_end_time'] ?? null,
                    'end_time' => $validated['end_time'],
                ]);
            } else {
                $schedule->update([
                    'start_time' => $validated['start_time'],
                    'break_start_time' => $validated['break_start_time'] ?? null,
                    'break_end_time' => $validated['break_end_time'] ?? null,
                    'end_time' => $validated['end_time'],
                ]);
            }
        }

        notify()->success('Schedule saved successfully', 'Success');
        return redirect()->route('appointment-schedules.index');
    }

    public function edit(AppointmentSchedule $schedule)
    {
        $blockTime = BlockTime::first();
        return view('appointment_schedules.edit', compact('schedule', 'blockTime'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'days' => ['required', 'array'],
            'days.*' => ['string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'start_time' => [
                'required',
                Rule::when($request->filled('break_start_time'), ['before:break_start_time'], ['before:end_time'])
            ],
            'break_start_time' => ['nullable', 'after:start_time', 'required_with:break_end_time'],
            'break_end_time' => ['nullable', 'after:break_start_time', 'required_with:break_start_time'],
            'end_time' => [
                'required',
                Rule::when($request->filled('break_end_time'), ['after:break_end_time'], ['after:start_time'])
            ],
        ], [
            'days.required' => 'Please checked at least one day.',
            'start_time.before' => 'The start time must be before break start time or end time.',
            'end_time.after' => 'The end time must be after break end time or start time.'
        ]);

        foreach ($request->days as $day) {
            $schedule = AppointmentSchedule::where('day', $day)->first();

            if ($schedule) {
                $schedule->update([
                    'start_time' => $validated['start_time'],
                    'break_start_time' => $validated['break_start_time'] ?? null,
                    'break_end_time' => $validated['break_end_time'] ?? null,
                    'end_time' => $validated['end_time'],
                ]);
            }
        }

        notify()->success('Schedule Updated Successfully', 'Success');
        return redirect()->route('appointment-schedules.index');
    }

    public function destroy(AppointmentSchedule $schedule)
    {
        if (!$schedule->delete()) {
            notify()->error('Schedule Delete Failed', 'Error');
            return redirect()->route('appointment-schedules.index');
        }

        notify()->success('Schedule Deleted Successfully', 'Success');
        return redirect()->route('appointment-schedules.index');
    }
}
