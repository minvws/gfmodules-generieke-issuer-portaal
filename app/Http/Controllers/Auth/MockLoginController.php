<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class MockLoginController extends Controller
{
    public function __construct(
        protected readonly LoginResponseHandlerInterface $loginResponseHandler
    ) {
    }

    /**
     * Handle the mock login method when enabled.
     */
    public function login(): Response
    {
        return $this->loginResponseHandler->handleLoginResponse(
            (object)[
                    "organization_code" => "12341234",
                ]
        );
    }
}
