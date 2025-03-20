<?php

declare(strict_types=1);

namespace App\Dto;

class CredentialData
{
    public function __construct(
        protected ?string $subject = null,
    ) {
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getSubjectAsArray(): array
    {
        return json_decode($this->subject, true, 512, JSON_THROW_ON_ERROR);
    }
}
