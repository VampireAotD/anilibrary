<?php

declare(strict_types=1);

namespace App\Broadcasting\Scraper;

use App\Models\User;

final class ScrapeAnimeChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     * @phpstan-ignore-next-line Ignored because of unused return type
     */
    public function join(User $user, string $requestedId): array | bool
    {
        return $user->id === $requestedId;
    }
}
