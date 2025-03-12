<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\UziUser;

class FlowState
{
    public function __construct(
        protected ?UziUser $user = null,
        protected ?ConsentData $consentData = null,
        protected ?AuthorizationData $authorizationData = null,
    ) {
    }

    public function getUser(): ?UziUser
    {
        return $this->user;
    }

    public function getConsentData(): ?ConsentData
    {
        return $this->consentData;
    }

    public function getAuthorizationData(): ?AuthorizationData
    {
        return $this->authorizationData;
    }

    public function isFlowComplete(): bool
    {
        return $this->user && $this->consentData && $this->authorizationData;
    }
}
