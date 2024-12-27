<?php

namespace App\Providers;

use App\Models\AccessRequest;
use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('accessAdminPages', function (User $user) {
            return $user->role->role_name === "Admin";
        });

        Gate::define('accessRestrictedPages', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Manager', 'Staff']);
        });

        Gate::define('accessAdminAndManagerPages', function (User $user) {
            return in_array($user->role->role_name, ['Admin', 'Manager']);
        });

        Gate::define('isClient', function (User $user) {
            return $user->role->role_name === "Client";
        });

        // Custom globally available variable
        View::composer('*', function ($view) {
            $unreadMessages = Message::where('viewed', false)->count();
            $view->with('unreadMessages', $unreadMessages);
        });

        View::composer('*', function ($view) {
            $accessRequestsCount = AccessRequest::count();
            $view->with('accessRequestsCount', $accessRequestsCount);
        });
    }
}
