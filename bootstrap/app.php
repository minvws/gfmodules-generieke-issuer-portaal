<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustHosts(static function (): array {
            return Config::array('app.trusted_hosts');
        });
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\Locale::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
