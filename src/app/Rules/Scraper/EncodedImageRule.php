<?php

declare(strict_types=1);

namespace App\Rules\Scraper;

use App\Enums\Validation\Scraper\EncodedImageRuleEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Class EncodedImageRule
 * @package App\Rules\Scraper
 */
final class EncodedImageRule implements ValidationRule
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
        if (
            $value === config('cloudinary.default_image')
            || preg_match('#data:(image/jpeg|image/jpg|image/png|image/gif|image/webp);base64,.#mi', $value)
        ) {
            return;
        }

        $fail(EncodedImageRuleEnum::INVALID_ENCODING->value);
    }
}