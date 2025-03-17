<?php

declare(strict_types=1);

namespace App\Rules\Telegram;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

final class SupportedUrlRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string                                       $attribute
     * @param mixed                                        $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array(preg_match('#(animego\.org|animevost\.org)#mi', (string) $value), [0, false], true)) {
            $fail(__('validation.telegram.url'));
        }
    }
}
