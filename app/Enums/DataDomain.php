<?php

declare(strict_types=1);

namespace App\Enums;

enum DataDomain: string
{
    case ImagingStudy = 'ImagingStudy';
    case MedicationStatement = 'MedicationStatement';

    /**
     * @param string[] $dataDomains
     * @return self[]
     */
    public static function fromStringArray(array $dataDomains): array
    {
        return array_map(static fn(string $dataDomain) => self::from($dataDomain), $dataDomains);
    }

    /**
     * @param self[] $dataDomains
     * @return string[]
     */
    public static function toStringArray(array $dataDomains): array
    {
        return array_map(static fn(self $dataDomain) => $dataDomain->value, $dataDomains);
    }
}
