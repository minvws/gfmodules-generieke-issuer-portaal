<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AuthGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        $this->bootAuth();
    }

    public function bootAuth(): void
    {
        Auth::extend('session', function (Application $app, string $name, array $config) {
            return new AuthGuard($app->make('session')->driver(), $app->make('events'));
        });
    }
}
