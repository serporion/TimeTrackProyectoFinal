<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use Illuminate\Support\Facades\URL; //NGROKPRUEBA

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
        Vite::prefetch(concurrency: 3);

        Inertia::share('auth.user', function () {
            return Auth::check() ? Auth::user() : null;
        });

        // NGROKPRUEBA o produccion

        if (app()->environment('production') || str_contains(config('app.url'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        }

    }
}
