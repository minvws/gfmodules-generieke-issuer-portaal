<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\User;

class FlowState
{
    public function __construct(
        protected ?User $user = null,
        protected ?CredentialData $credentialData = null,
    ) {
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getCredentialData(): ?CredentialData
    {
        return $this->credentialData;
    }

    public function isFlowComplete(): bool
    {
        return $this->user
            && $this->credentialData
            && $this->credentialData->getSubject()
            && $this->credentialData->getSubjectAsArray();
    }
}
