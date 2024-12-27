<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;

class PermissionController extends Controller
{

    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Permission::search($search);

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
        $permissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        return view('permissions.index', [
            'permissions' => $permissions,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    // public function index_old()
    // {
    //     $search = trim(request('search')) ?? '';
    //     $perPage = 15;
    //     if ($search === "") {
    //         $permissions = Permission::orderBy('id', 'desc')->paginate($perPage);
    //     } else {
    //         $permissions = Permission::search($search)
    //             ->orderBy('id', 'desc')
    //             ->paginate($perPage)
    //             ->appends([
    //                 'search' => $search,
    //             ]);
    //     }

    //     return view('permissions.index', compact('permissions', 'search', 'perPage'));
    // }


    public function create()
    {
        return view('permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();

        $permission = new Permission;
        $permission->permission_name =  ucfirst(strip_tags($validated['permission_name']));
        $permission->description = strip_tags(trim($validated['description']));

        if (!$permission->save()) {
            notify()->error('Permission Creation Failed', 'Error');
            return redirect()->route('permissions.create');
        }

        notify()->success('Permission Created Successfully', 'Success');
        return redirect()->route('permissions.create');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();

        $permission->permission_name = ucfirst(strip_tags($validated['permission_name']));
        $permission->description = strip_tags(trim($validated['description']));
        if (!$permission->save()) {
            notify()->error('Permission Update Failed', 'Error');
            return redirect()->route('permissions.edit', ['permission' => $permission->id]);
        }

        notify()->success('Permission Updated Successfully', 'Success');
        return redirect()->route('permissions.edit', ['permission' => $permission->id]);
    }

    public function destroy(Permission $permission)
    {
        if (!$permission->delete()) {
            notify()->error('Permission Delete Failed', 'Error');
            return redirect()->route('permissions.index');
        }

        notify()->success('Permission Deleted Successfully', 'Success');
        return redirect()->route('permissions.index');
    }
}
