<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CredentialData;
use App\Dto\FlowState;
use App\Services\Uzi\OidcAuthGuard;
use Illuminate\Contracts\Session\Session;

class FlowStateService
{
    public function __construct(
        protected OidcAuthGuard $uziAuthGuard,
        protected Session $session,
    ) {
    }

    public function getFlowStateFromSession(): FlowState
    {
        $user = $this->uziAuthGuard->user();

        return new FlowState(
            user: $user,
            credentialData: $this->getCredentialDataFromSession(),
        );
    }

    public function setCredentialDataInSession(CredentialData $state): void
    {
        $this->session->put('flow_credential_data', $state);
    }

    public function clearFlowState(): void
    {
        $this->session->forget('flow_credential_data');
    }

    protected function getCredentialDataFromSession(): ?CredentialData
    {
        return $this->session->get('flow_credential_data');
    }
}
