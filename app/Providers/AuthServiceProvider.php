<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Uzi\OidcAuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        Auth::extend('oidc', function ($app, $name, array $config) {
            return new OidcAuthGuard($app->make('session')->driver(), $app->make('events'));
        });
    }
}
