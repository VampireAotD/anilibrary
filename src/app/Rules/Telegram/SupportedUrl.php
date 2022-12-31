<?php

declare(strict_types=1);

namespace App\Rules\Telegram;

use App\Enums\Validation\SupportedUrlEnum;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class SupportedUrl
 * @package App\Rules\Telegram
 */
class SupportedUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match('#(animego\.org|animevost\.org)#mi', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return SupportedUrlEnum::UNSUPPORTED_URL->value;
    }
}
