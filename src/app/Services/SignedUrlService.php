<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

final class SignedUrlService
{
    public function createRegistrationLink(string $email): string
    {
        $hash           = hash('sha256', $email);
        $expirationTime = now()->addMinutes(config('auth.registration_link_timeout', 30));

        $url = URL::temporarySignedRoute('register', $expirationTime, [
            'hash' => $hash,
        ]);

        Cache::add($hash, $email, $expirationTime);

        return $url;
    }
}
