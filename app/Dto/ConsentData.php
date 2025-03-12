<?php

declare(strict_types=1);

namespace App\Dto;

class ConsentData
{
    protected ?string $bsn = null;
    protected ?string $birthYear = null;
    protected bool $consent = false;

    public function __construct(
        ?string $bsn = null,
        ?string $birthYear = null,
        bool $consent = false
    ) {
        $this->bsn = $bsn;
        $this->birthYear = $birthYear;
        $this->consent = $consent;
    }

    public function getBsn(): ?string
    {
        return $this->bsn;
    }

    public function getBirthYear(): ?string
    {
        return $this->birthYear;
    }

    public function getConsent(): bool
    {
        return $this->consent;
    }
}
