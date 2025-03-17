<?php

declare(strict_types=1);

namespace App\Rules\Scraper;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

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
            in_array(preg_match('#data:image/(jpeg|jpg|png|gif|webp);base64,.#mi', (string) $value), [0, false], true)
            && $value !== config('cloudinary.default_image')
        ) {
            $fail(__('validation.scraper.image', ['attribute' => $attribute]));
        }
    }
}
