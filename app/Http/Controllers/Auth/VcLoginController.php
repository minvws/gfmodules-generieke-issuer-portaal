<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\VCVerifierService;
use Exception;
use Illuminate\View\View;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class VcLoginController extends Controller
{
    public function __construct(
        protected VCVerifierService $vcVerifierService,
        protected readonly LoginResponseHandlerInterface $loginResponseHandler
    ) {
    }

    public function login(): View
    {
        $presentation = $this->vcVerifierService->initializePresentationSession(
            credentialType: "MijnGeneriekeCredential",
            // $id will be replaced by the session ID by the verifier
            successRedirectUrl: urldecode(url(route('vc.login-session', ['sessionId' => '$id']))),
            errorRedirectUrl: urldecode(url(route('vc.login-session', ['sessionId' => '$id', 'error' => true]))),
        );

        return view('auth.vc')->with('vpUrl', $presentation->getUrl());
    }

    public function session(string $sessionId): Response
    {
        try {
            $session = $this->vcVerifierService->getPresentationSession($sessionId);
        } catch (Exception) {
            return redirect()
                ->route('flow')
                ->withErrors(['login' => trans('Login session could not be found. Please try again.')]);
        }

        return $this->loginResponseHandler->handleLoginResponse(
            (object)[$session->getFirstCredentialSubject()]
        );
    }
}
