<?php

declare(strict_types=1);

namespace App\Rules\Telegram;

use App\Enums\Validation\Telegram\SupportedUrlRuleEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Class SupportedUrlRule
 * @package App\Rules\Telegram
 */
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
        if (!preg_match('#(animego\.org|animevost\.org)#mi', $value)) {
            $fail(SupportedUrlRuleEnum::UNSUPPORTED_URL->value);
        }
    }
}
