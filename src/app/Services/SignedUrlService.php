<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\URL;

/**
 * Class SignedUrlService
 * @package App\Services
 */
final class SignedUrlService
{
    public function createRegistrationUrl(): string
    {
        return URL::temporarySignedRoute('register', now()->addMinutes(config('auth.registration_link_timeout')));
    }
}
