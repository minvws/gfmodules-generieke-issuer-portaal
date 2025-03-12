<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\DataDomain;

class AuthorizationData
{
    /**
     * @var DataDomain[]
     */
    protected array $informationTypes = [];
    protected ?string $accessCode = null;

    /**
     * @param DataDomain[] $informationTypes
     * @param string|null $accessCode
     */
    public function __construct(
        array $informationTypes = [],
        ?string $accessCode = null
    ) {
        $this->informationTypes = $informationTypes;
        $this->accessCode = $accessCode;
    }

    /**
     * @return DataDomain[]
     */
    public function getInformationTypes(): array
    {
        return $this->informationTypes;
    }

    /**
     * @return string|null
     */
    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }
}
