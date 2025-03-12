<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\BsnService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BsnRule implements ValidationRule
{
    public function __construct(protected BsnService $bsnService)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Het burgerservicenummer is niet geldig.');
        }

        if (!$this->bsnService->isValid($value)) {
            $fail('Het burgerservicenummer is niet geldig.');
        }
    }
}
