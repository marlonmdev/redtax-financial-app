<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Enums\RoleType;
use Illuminate\View\View;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\LoginHistory;
use App\Enums\PermissionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AccessRequest;

class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
        $this->firstSetup();
        return view('auth.login');
    }

    public function clientLogin(): View
    {
        return view('auth.client-login');
    }

    public function firstSetup()
    {
        if (Permission::count() == 0) {
            // Create the permissions
            foreach (PermissionType::cases() as $permissionType) {
                DB::table('permissions')->updateOrInsert(
                    ['permission_name' => $permissionType->value],
                    ['description' => $permissionType->value . ' permission']
                );
            }
        }

        if (Role::count() == 0) {
            // Create the roles
            foreach (RoleType::cases() as $roleType) {
                $role = Role::firstOrCreate(
                    ['role_name' => $roleType->value],
                    ['description' => $roleType->value . ' role']
                );

                $permissions = match ($roleType) {
                    RoleType::ADMIN => PermissionType::cases(),
                    RoleType::MANAGER => [PermissionType::CREATE, PermissionType::READ, PermissionType::UPDATE],
                    RoleType::STAFF => [PermissionType::CREATE, PermissionType::READ],
                    RoleType::CLIENT => [PermissionType::READ],
                };

                // Attach permissions to the role
                foreach ($permissions as $permissionType) {
                    $permission = Permission::where('permission_name', $permissionType->value)->first();
                    if ($permission) {
                        DB::table('role_permissions')->updateOrInsert(
                            ['role_id' => $role->id, 'permission_id' => $permission->id],
                            []
                        );
                    }
                }
            }
        }

        if (User::count() == 0) {
            $adminConfig = config('admin.default_admin');

            // Retrieve the role_id of the 'Admin' role
            $adminRole = Role::where('role_name', RoleType::ADMIN->value)->first();

            if ($adminRole) {
                User::create([
                    'avatar' => null,
                    'name' => $adminConfig['name'],
                    'email' => $adminConfig['email'],
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make($adminConfig['password']),
                    'active' => 1,
                    'role_id' => $adminRole->id ?? null
                ]);
            } else {
                // Handle the case where the Admin role is not found (optional)
                throw new Exception('Admin role not found. Please ensure roles are properly seeded.');
            }
        }
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate the user
        $request->authenticate();

        // Get the authenticated user
        $user = $request->user();

        // Check if the user has access
        if ($user->has_access === 0) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // Check if the user has the role of 'Client'
        if ($user->role && $user->role->role_name == 'Client') {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors([
                'email' => 'Clients are not allowed to login using the employee login form. Please go to client login.',
            ]);
        }


        // Regenerate the session to prevent session fixation
        $request->session()->regenerate();

        // Store successfull login attempts to Login History
        $this->storeLoginHistory($request, $user->id, $user->name);

        // Redirect the user to the intended location
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function storeLoginHistory($request, $user_id, $user_name)
    {
        $loginHistory = new LoginHistory();
        $loginHistory->user_id = $user_id;
        $loginHistory->name = $user_name;
        $loginHistory->ip_address = $request->ip();
        $loginHistory->user_agent = $request->header('User-Agent');
        $loginHistory->save();
    }

    public function clientRequestAccess(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email']
        ]);

        $existingUser = DB::table('users')
            ->where('email', $validated['email'])
            ->first();

        if (!$existingUser) {
            return back()->withErrors([
                'email' => 'We can\'t find a client with that email address.',
            ]);
        } else {
            if ($existingUser->has_access === 0) {
                return back()->withErrors([
                    'email' => 'The account associated with this email address has been deactivated.',
                ]);
            }

            // Attempt to authenticate using email and password
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // If successful, redirect to intended page
                return redirect()->intended('dashboard');
            }

            // If authentication fails, redirect back with an error message
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return redirect()->route('client-login');
    }


    public function loginWithEmailOnly(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
            ],
            ['email.required' => 'Please enter your email address.']
        );

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        if ($user->role && $user->role->role_name !== 'Client') {
            return back()->withErrors(['email' => 'You are not allowed to request access.']);
        }

        // Check if has_access and within the 24-hours
        if ($user->has_access == 1 && $user->access_granted_at && $this->withinAccessDuration($user->access_granted_at)) {
            // Log the user in
            Auth::login($user);
            // Store successfull login attempts to Login History
            $this->storeLoginHistory($request, $user->id, $user->name);

            return redirect()->intended('dashboard');
        }

        $existingRequest = DB::table('access_requests')
            ->where('email', $request->email)
            ->first();

        if ($existingRequest) {
            return back()->withErrors(['email' => 'You still have a pending access request waiting for approval.']);
        }

        $accessRequest = AccessRequest::create(
            [
                'email' => $user->email,
                'name' => $user->name,
                'user_id' => $user->id,
                'requested_at' => now(),
            ]
        );

        if (!$accessRequest->save()) {
            return back()->withErrors(['email' => 'Unable to send access request']);
        }

        return back()->with('success', 'Your access request was sent successfully');
    }

    protected function withinAccessDuration($accessGrantedAt)
    {
        // Check if the current time is within 24 hours of access_granted_at
        $accessExpiry = Carbon::parse($accessGrantedAt)->addHours(24);
        return Carbon::now()->lessThanOrEqualTo($accessExpiry);
    }

    // public function store_old(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
