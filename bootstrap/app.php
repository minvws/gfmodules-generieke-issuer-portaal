<?php

use App\Console\Commands\VerifyCredential;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Config;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        health: '/up',
    )
    ->withCommands([
        VerifyCredential::class
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustHosts(static function (): array {
            $trustedHosts = Config::array('app.trusted_hosts');

            /**
             * Convert each trusted host to a pattern to match the specific configured host
             * As Symfony Request::setTrustedHosts expects regex patterns.
             */
            return Arr::map($trustedHosts, static function (string $host) {
                return sprintf('^%s$', preg_quote($host, '/'));
            });
        }, subdomains: false);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\Locale::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
$app->useEnvironmentPath(getenv('APP_ENV_PATH') ?: '');
return $app;
