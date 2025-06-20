<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\OidcLoginResponseHandler;
use App\Services\OidcExceptionHandler;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use MinVWS\OpenIDConnectLaravel\OpenIDConnectServiceProvider;
use MinVWS\OpenIDConnectLaravel\Services\ExceptionHandlerInterface;

class OidcServiceProvider extends OpenIDConnectServiceProvider
{
    protected function registerExceptionHandler(): void
    {
        $this->app->bind(ExceptionHandlerInterface::class, OidcExceptionHandler::class);
    }

    protected function registerResponseHandler(): void
    {
        $this->app->bind(LoginResponseHandlerInterface::class, OidcLoginResponseHandler::class);
    }
}
