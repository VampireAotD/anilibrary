<?php

declare(strict_types=1);

namespace App\Services\Url;

use Illuminate\Support\Facades\URL;

final readonly class SignedUrlService
{
    public function createRegistrationLink(string $invitationId): string
    {
        $expiresAt = now()->addMinutes(config('auth.registration_link_timeout', 30));

        return URL::temporarySignedRoute('register', $expiresAt, [
            'invitationId' => $invitationId,
        ]);
    }
}
