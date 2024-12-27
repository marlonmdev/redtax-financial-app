<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

class UserController extends Controller
{

    public function index()
    {
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $users = User::with('roles')->orderBy('id', 'desc')->paginate($perPage);
        } else {
            $users = User::search($search)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends(['search' => $search,]);
            $users->load('roles');
        }

        return view('users.index', compact('users', 'search', 'perPage'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['name'] = ucfirst(strip_tags($validated['name']));
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User Creation Failed.');
        }

        foreach ($validated['role_id'] as $role_id) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role_id
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User Created Successfully.');
    }

    public function edit($id)
    {
        $roles = Role::all();
        $user = User::with('roles')->findOrFail($id);
        $currentRoles = collect($user->roles)->pluck('pivot.role_id')->toArray();

        return view('users.edit', compact('user', 'roles', 'currentRoles'));
    }

    public function update_profile(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name = ucfirst(strip_tags($validated['name']));
        $user->email = strip_tags($validated['email']);
        $user->save();

        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User Profile Updated Successfully.');
    }

    public function update_role(StoreUserRoleRequest $request, User $user)
    {
        // Validate the incoming request
        $validated = $request->validated();

        // Get current roles assigned to the user
        $currentRoles = UserRole::where('user_id', $user->id)->pluck('role_id')->toArray();

        // Determine roles to add and remove
        $rolesToAdd = array_diff($validated['role_id'], $currentRoles);
        $rolesToRemove = array_diff($currentRoles, $validated['role_id']);

        // Add new roles
        foreach ($rolesToAdd as $roleId) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $roleId
            ]);
        }

        // Remove old roles
        UserRole::where('user_id', $user->id)
            ->whereIn('role_id', $rolesToRemove)
            ->delete();

        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User Role Updated Successfully.');
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('users.edit', ['user' => $user->id])->with('success', 'User Password Updated Successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
    }
}
