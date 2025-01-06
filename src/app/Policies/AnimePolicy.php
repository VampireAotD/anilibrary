<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Anime;
use App\Models\User;

final class AnimePolicy
{
    /**
     * Determine whether the user can update the anime in the list.
     */
    public function updateInList(User $user, Anime $anime): bool
    {
        return $user->animeList->contains($anime->id);
    }

    /**
     * Determine whether the user can remove the anime from the list.
     */
    public function removeFromList(User $user, Anime $anime): bool
    {
        return $user->animeList->contains($anime->id);
    }
}
