<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AuthorizationData;
use App\Dto\ConsentData;
use App\Dto\FlowState;
use App\Services\Uzi\OidcAuthGuard;
use Illuminate\Contracts\Session\Session;

class FlowStateService
{
    public function __construct(
        protected OidcAuthGuard $uziAuthGuard,
        protected Session       $session,
    ) {
    }

    public function getFlowStateFromSession(): FlowState
    {
        $user = $this->uziAuthGuard->user();

        return new FlowState(
            user: $user,
            consentData: $this->getConsentFromSession(),
            authorizationData: $this->getAuthorizationFromSession(),
        );
    }

    public function setConsentDataInSession(ConsentData $state): void
    {
        $this->session->put('flow_consent_data', $state);
    }

    public function setAuthorizationDataInSession(AuthorizationData $state): void
    {
        $this->session->put('flow_authorization_data', $state);
    }

    public function clearFlowState(): void
    {
        $this->session->forget('flow_consent_data');
        $this->session->forget('flow_authorization_data');
    }

    protected function getConsentFromSession(): ?ConsentData
    {
        return $this->session->get('flow_consent_data');
    }

    protected function getAuthorizationFromSession(): ?AuthorizationData
    {
        return $this->session->get('flow_authorization_data');
    }
}
