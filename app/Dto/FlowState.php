<?php

declare(strict_types=1);

namespace App\Dto;

class FlowState
{
    public function __construct(
        protected ?CredentialData $credentialData = null,
    ) {
    }

    public function getCredentialData(): ?CredentialData
    {
        return $this->credentialData;
    }

    public function isFlowComplete(): bool
    {
        return $this->credentialData
            && $this->credentialData->getSubject()
            && $this->credentialData->getSubjectAsArray();
    }
}
