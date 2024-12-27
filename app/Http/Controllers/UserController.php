<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Mail\AccessActivatedEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

class UserController extends Controller
{
    public function index()
    {
        $search = trim(request('search', ''));
        $perPage = (int) request('per_page', 10);
        $page = (int) request('page', 1);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction', 'desc');

        // Perform the search with Laravel Scout
        $query = User::search($search);

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
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $sortedResults->count(), // Total number of items
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()] // Maintain query parameters
        );

        // Load relationships
        $users->load('role');

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    // public function index_old()
    // {
    //     $search = trim(request('search', ''));
    //     $page = (int) request('page', 1);
    //     $perPage = (int) request('per_page', 10);

    //     $sortField = request('sort_field', 'id');
    //     $sortDirection = request('sort_direction',  'desc');

    //     if ($search === "") {
    //         $users = User::with('roles')
    //             ->orderBy($sortField, $sortDirection)
    //             ->paginate($perPage, ['*'], 'page', $page);
    //     } else {
    //         $users = User::search($search)
    //             ->orderBy($sortField, $sortDirection)
    //             ->paginate($perPage, ['*'], 'page', $page)
    //             ->appends(['search' => $search, 'per_page' => $perPage]);
    //         $users->load('roles');
    //     }

    //     return view(
    //         'users.index',
    //         compact(
    //             'users',
    //             'search',
    //             'perPage',
    //             'sortField',
    //             'sortDirection'
    //         )
    //     );
    // }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $validated['name'] = ucwords(strip_tags($validated['name']));
        $validated['email'] = $validated['email'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = $validated['role'];

        $user = User::create($validated);

        if (!$user) {
            notify()->error('User Creation Failed', 'Error');
            return redirect()->route('users.index');
        }

        notify()->success('User Created Successfully', 'Success');
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user = User::with('role')->findOrFail($user->id);
        // $currentRoles = collect($user->roles)->pluck('pivot.role_id')->toArray();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update_profile(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name = ucwords(strip_tags($validated['name']));
        $user->email = strip_tags($validated['email']);
        if (!$user->save()) {
            notify()->error('User Profile Update Failed', 'Error');
            return redirect()->route('users.edit', ['user' => $user->id]);
        }

        notify()->success('User Profile Updated Successfully', 'Success');
        return redirect()->route('users.edit', ['user' => $user->id]);
    }

    public function update_role(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required'
        ]);

        $updated = User::where('id', $user->id)->update([
            'role_id' => $validated['role'],
        ]);

        if (!$updated) {
            notify()->error('User Role Update Failed', 'Error');
        }

        notify()->success('User Role Updated Successfully', 'Success');
        return redirect()->route('users.edit', ['user' => $user->id]);
    }

    // public function update_role_old(StoreUserRoleRequest $request, User $user)
    // {
    //     // Validate the incoming request
    //     $validated = $request->validated();

    //     // Get current roles assigned to the user
    //     $currentRoles = UserRole::where('user_id', $user->id)->pluck('role_id')->toArray();

    //     // Determine roles to add and remove
    //     $rolesToAdd = array_diff($validated['role_id'], $currentRoles);
    //     $rolesToRemove = array_diff($currentRoles, $validated['role_id']);

    //     // Add new roles
    //     foreach ($rolesToAdd as $roleId) {
    //         UserRole::create([
    //             'user_id' => $user->id,
    //             'role_id' => $roleId
    //         ]);
    //     }

    //     // Remove old roles
    //     UserRole::where('user_id', $user->id)
    //         ->whereIn('role_id', $rolesToRemove)
    //         ->delete();

    //     notify()->success('User Role Updated Successfully', 'Success');
    //     return redirect()->route('users.edit', ['user' => $user->id]);
    // }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->password = Hash::make($validated['password']);
        if (!$user->save()) {
            notify()->error('User Password Update Failed', 'Error');
            return redirect()->route('users.edit', ['user' => $user->id]);
        }

        notify()->success('User Password Updated Successfully', 'Success');
        return redirect()->route('users.edit', ['user' => $user->id]);
    }

    public function activate_account(User $user)
    {
        if ($user->role->role_name === 'Client') {
            $activate = $user->update([
                'has_access' => 1,
                'access_granted_at' => now()
            ]);

            if ($activate) {
                $details = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                // Send email after successful activation
                Mail::to($user->email)->send(new AccessActivatedEmail($details));

                $message = 'User Account Activated, Client Access Duration is Within 24 Hours';
            } else {
                notify()->error('User Account Activation Failed', 'Error');
                return back();
            }
        } else {
            $activate = $user->update([
                'has_access' => 1
            ]);

            if ($activate) {
                $message = 'User Account Activated';
            } else {
                notify()->error('User Account Activation Failed', 'Error');
                return back();
            }
        }

        notify()->success($message, 'Success');
        return back();
    }


    public function deactivate_account(User $user)
    {
        $deactivate = $user->update([
            'has_access' => 0
        ]);

        if (!$deactivate) {
            notify()->error('User Account Deactivation Failed', 'Error');
            return back();
        }

        notify()->success('User Account Deactivated', 'Success');
        return back();
    }

    public function agreeToTerms(User $user)
    {
        $agree = $user->update([
            'agreed_to_terms' => 1,
            'agreed_on' => now()
        ]);

        if (!$agree) {
            notify()->error('Unable to Agree to Terms and Conditions', 'Error');
            return redirect()->route('dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function destroy(User $user)
    {
        $fullName = $user->name;
        $userId = $user->id;
        $userRole = $user->role->role_name;

        if ($user->role && $user->role->role_name === 'Client') {
            DB::table('clients')->where('user_id', $userId)->update(['user_id' => null]);
        }

        if (!$user->delete()) {
            notify()->error('User Deletion Failed', 'Error');
            return redirect()->route('users.index');
        }

        // Save the delete log
        $auditLog = new AuditLog;
        $auditLog->user_id = auth()->user()->id;
        $auditLog->name = auth()->user()->name;
        $auditLog->activity = "Deleted a " . $userRole . " user account for " . $fullName;
        $auditLog->save();

        notify()->success('User Deleted Successfully', 'Success');
        return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
    }

    public function checkEmail(Request $request)
    {
        // Check if the email exists in the users table
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $emailExists
        ]);
    }
}
