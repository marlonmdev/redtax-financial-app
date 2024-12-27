<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRolePermissionRequest;
use App\Models\RolePermission;

class RolePermissionController extends Controller
{
    public function create(Role $role)
    {
        $permissions = Permission::all();
        return view('role_permissions.create', ['role' => $role, 'permissions' => $permissions]);
    }


    public function store(StoreRolePermissionRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['permission_id'] as $permission_id) {
            RolePermission::create([
                'role_id' => $validated['role_id'],
                'permission_id' => $permission_id
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role Permissions Assigned Successfully.');
    }
}
