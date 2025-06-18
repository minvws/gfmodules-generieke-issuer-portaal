<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Session\Session;
use App\Dto\CredentialData;
use App\Services\EnrichService;
use App\Services\FlowStateService;
use JsonException;


class OidcLoginResponseHandler implements LoginResponseHandlerInterface
{
    public function __construct(
        protected Session $session,
        protected FlowStateService $stateService,
        protected EnrichService $enrichService
    ) {
    }

    /**
     * @param object{
    *     userinfo: string
     *  } $userInfo
     */
    public function handleLoginResponse(object $userInfo): Response
    {
        $user = User::deserializeFromObject($userInfo);
        if ($user === null) {
            return redirect()
                ->route('index')
                ->with('error', __('Something went wrong with logging in, please try again.'));
        }
        if (!$user->getUserInfo()) {
            throw new Exception('User does not have valid userinfo.');
        }

        $data = new CredentialData(
            subject: $user->getUserInfo(),
        );

        try {
            $credentialSubject = $data->getSubjectAsArray();
            $credentialSubject = $this->enrichService->enrich($credentialSubject);
            $cs = json_encode($credentialSubject, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            return redirect()
                ->route('index')
                ->with('error', __('Something went wrong with logging in, '))
                ->with('error_description', __($e->getMessage()));
        }

        Auth::setUser($user);

        $data = new CredentialData(
            subject: $cs,
        );

        $this->stateService->setCredentialDataInSession($data);
        $this->session->save();

        return new RedirectResponse(route('flow'));
    }
}
