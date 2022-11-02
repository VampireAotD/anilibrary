<?php

declare(strict_types=1);

namespace App\Rules\Telegram;

use Illuminate\Contracts\Validation\Rule;

class ValidBase64EncodedImage implements Rule
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
        return $value === config('cloudinary.default_image') ||
            preg_match(
                '#data:(image/jpeg|image/jpg|image/png|image/gif|image/webp);base64,\w+#mi',
                $value
            );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid encoded image';
    }
}
