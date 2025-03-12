<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class DigidMockController extends Controller
{
    public function __construct(
        private readonly LoginResponseHandlerInterface $loginResponseHandler
    ) {
    }

    public function login(): Response
    {
        return $this->loginResponseHandler->handleLoginResponse(
            (object)[
                "relations" => [],
                "initials" => "m",
                "surname" => "Boss",
                "surname_prefix" => "",
                "uzi_id" => "12312123",
                "loa_uzi" => "Basis",
            ]
        );
    }
}
