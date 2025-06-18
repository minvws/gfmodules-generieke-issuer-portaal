<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class NoopLoginController extends Controller
{

    public function __construct(
        protected readonly LoginResponseHandlerInterface $loginResponseHandler
    ) {
    }

    /**
     * Handle a login request for the application when login method is 'none'.
     */
    public function login(): Response
    {
        return $this->loginResponseHandler->handleLoginResponse(
            (object)[
                "userinfo" => json_encode([
                    "organization_code" => "1234567890",
                ]),
            ]
        );
    }
}
