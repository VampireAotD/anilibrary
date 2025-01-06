<?php

declare(strict_types=1);

namespace App\Services\Url;

use App\Helpers\Registration;
use Illuminate\Support\Facades\URL;

final readonly class SignedUrlService
{
    public function createRegistrationLink(string $invitationId): string
    {
        $expiresAt = Registration::expirationDate();

        return URL::temporarySignedRoute('register', $expiresAt, [
            'invitationId' => $invitationId,
        ]);
    }
}
