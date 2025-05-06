<?php

declare(strict_types=1);

namespace App\Dto;

class PresentationSessionInitiated
{
    public function __construct(
        protected string $url,
        protected string $sessionId,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}
