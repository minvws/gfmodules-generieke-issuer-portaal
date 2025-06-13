<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\CredentialData;
use App\Dto\FlowState;
use Illuminate\Contracts\Session\Session;

class FlowStateService
{
    private const FLOW_CREDENTIAL_DATA_KEY = 'flow_credential_data';

    public function __construct(
        protected Session $session,
    ) {
    }

    public function getFlowStateFromSession(): FlowState
    {
        return new FlowState(
            credentialData: $this->session->get(self::FLOW_CREDENTIAL_DATA_KEY),
        );
    }

    public function setCredentialDataInSession(CredentialData $state): void
    {
        $this->session->put(self::FLOW_CREDENTIAL_DATA_KEY, $state);
    }

    public function clearFlowState(): void
    {
        $this->session->forget(self::FLOW_CREDENTIAL_DATA_KEY);
    }
}
