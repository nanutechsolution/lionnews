<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Gate untuk mengakses admin panel (hanya Editor dan Admin)
        Gate::define('access-admin-panel', function ($user) {
            return $user->role === 'admin' || $user->role === 'editor';
        });

        // Gate untuk mempublikasi/mengubah status artikel (hanya Editor dan Admin)
        Gate::define('publish-article', function ($user) {
            return $user->role === 'admin' || $user->role === 'editor';
        });

        // Gate untuk admin penuh (hanya Admin)
        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });
    }
}
