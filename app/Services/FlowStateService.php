<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CredentialData;
use App\Dto\FlowState;
use Illuminate\Contracts\Session\Session;

class FlowStateService
{
    public function __construct(
        protected Session $session,
    ) {
    }

    public function getFlowStateFromSession(): FlowState
    {
        return new FlowState(
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
