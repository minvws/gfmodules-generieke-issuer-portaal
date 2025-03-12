<?php

declare(strict_types=1);

namespace App\Services;

class BsnService
{
    public function isValid(string $bsn): bool
    {
        if (strlen($bsn) == 8) {
            $bsn = '0' . $bsn;
        }
        if (strlen($bsn) != 9) {
            return false;
        }

        $sum = 9 * (int)$bsn[0] +
            8 * (int)$bsn[1] +
            7 * (int)$bsn[2] +
            6 * (int)$bsn[3] +
            5 * (int)$bsn[4] +
            4 * (int)$bsn[5] +
            3 * (int)$bsn[6] +
            2 * (int)$bsn[7] +
            -1 * ((int)($bsn[8] ?? 0));

        return $sum % 11 == 0;
    }
}
