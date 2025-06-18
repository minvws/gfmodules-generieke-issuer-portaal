<?php

declare(strict_types=1);

namespace App\Dto;

use JsonException;

class CredentialData
{
    public function __construct(
        protected ?string $subject = null,
    ) {}

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSubjectAsArray(): array
    {
        if ($this->subject === null) {
            return [];
        }

        try {
            return json_decode($this->subject, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
    }
}
