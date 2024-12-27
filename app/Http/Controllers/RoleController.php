<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use App\Http\Requests\StoreRoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $roles = Role::with('permissions')->orderBy('id', 'desc')->paginate($perPage);
        } else {
            $roles = Role::search($search)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends([
                    'search' => $search,
                ]);
            $roles->load('permissions');
        }

        return view('roles.index', ['roles' => $roles, 'search' => $search, 'perPage' => $perPage]);
    }

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
            return redirect()->route('roles.index')->with('error', 'Role Creation Failed.');
        }

        foreach ($validated['permission_id'] as $permission_id) {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permission_id
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role Created Successfully.');
    }

    public function edit($id)
    {
        $permissions = Permission::all();
        $role = Role::with('permissions')->findOrFail($id);
        $currentPermissions = collect($role->permissions)->pluck('pivot.permission_id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'currentPermissions'));
    }

    public function update(StoreRoleRequest $request, Role $role)
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

        return redirect()->route('roles.edit', ['role' => $role->id])->with('success', 'Role Updated Successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
