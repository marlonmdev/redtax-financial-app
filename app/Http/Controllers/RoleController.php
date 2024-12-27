<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{

    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = Role::search($search);

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
        $roles = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        // Load relationships
        $roles->load('permissions');

        return view('roles.index', [
            'roles' => $roles,
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
    //         $roles = Role::with('permissions')->orderBy('id', 'desc')->paginate($perPage);
    //     } else {
    //         $roles = Role::search($search)
    //             ->orderBy('id', 'desc')
    //             ->paginate($perPage)
    //             ->appends([
    //                 'search' => $search,
    //             ]);
    //         $roles->load('permissions');
    //     }

    //     return view('roles.index', compact('roles', 'search', 'perPage'));
    // }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        $validated['name'] = ucfirst(strip_tags($validated['role_name']));
        $validated['description'] = strip_tags(trim($validated['description']));

        $role = Role::create($validated);

        if (!$role) {
            notify()->error('Role Creation Failed', 'Error');
            return redirect()->route('roles.index');
        }

        foreach ($validated['permission_id'] as $permission_id) {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permission_id
            ]);
        }

        notify()->success('Role Created Successfully', 'Success');
        return redirect()->route('roles.create');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role = Role::with('permissions')->findOrFail($role->id);
        $currentPermissions = collect($role->permissions)->pluck('pivot.permission_id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'currentPermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        $role->role_name = ucfirst(strip_tags($validated['role_name']));
        $role->description = strip_tags(trim($validated['description']));
        $role->save();

        // Get current permissions assigned to the role
        $currentPermissions = RolePermission::where('role_id', $role->id)->pluck('permission_id')->toArray();

        // Determine permissions to add and remove
        $permissionsToAdd = array_diff($validated['permission_id'], $currentPermissions);
        $permissionsToRemove = array_diff($currentPermissions, $validated['permission_id']);

        // Add new permissions
        foreach ($permissionsToAdd as $permissionId) {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permissionId
            ]);
        }

        // Remove old permissions
        RolePermission::where('role_id', $role->id)
            ->whereIn('permission_id', $permissionsToRemove)
            ->delete();

        notify()->success('Role Updated Successfully', 'Success');
        return redirect()->route('roles.edit', ['role' => $role->id]);
    }

    public function destroy(Role $role)
    {
        if (!$role->delete()) {
            notify()->error('Role Delete Failed', 'Error');
        }

        notify()->success('Role Deleted Successfully', 'Success');
        return redirect()->route('roles.index');
    }
}
