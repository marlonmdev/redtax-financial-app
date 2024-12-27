<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Service::search($search);

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
        $services = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );


        return view('appointment_services.index', [
            'services' => $services,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('appointment_services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service' => ['required', Rule::unique('services', 'service')],
            'duration' => ['required', Rule::in(['30', '60', '90', '120', '150', '180'])],
        ],  [
            'service.unique' => 'The service already exists. Please create a different service.',
        ]);

        $validated['service'] = ucwords($validated['service']);
        $service = Service::create($validated);

        if (!$service) {
            notify()->error('Service save failed', 'Error');
            return redirect()->route('appointment-services.index');
        }

        notify()->success('Service saved successfully', 'Success');
        return redirect()->route('appointment-services.index');
    }

    public function edit(Service $service)
    {
        return view('appointment_services.edit', compact('service'));
    }

    public function update(Service $service, Request $request)
    {
        $validated = $request->validate([
            'service' => ['required'],
            'duration' => ['required', Rule::in(['30', '60', '90', '120', '150', '180'])]
        ]);

        $validated['service'] = ucwords($validated['service']);

        if ($service->update($validated)) {
            notify()->success('Service updated successfully', 'Success');
        } else {
            notify()->error('Service update failed', 'Error');
        }

        return redirect()->route('appointment-services.index');
    }

    public function destroy(Service $service)
    {
        if (!$service->delete()) {
            notify()->error('Service deleted failed', 'Error');
            return redirect()->route('appointment-services.index');
        }

        notify()->success('Service deleted duccessfully', 'Success');
        return redirect()->route('appointment-services.index');
    }
}
