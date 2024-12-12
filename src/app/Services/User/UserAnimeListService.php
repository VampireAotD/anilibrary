<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\Service\UserAnimeList\UpdateAnimeInListDTO;
use App\Enums\UserAnimeList\StatusEnum;
use App\Models\Pivots\UserAnimeList;
use App\Models\User;

final class UserAnimeListService
{
    public function findById(User $user, string $animeId): ?UserAnimeList
    {
        // @phpstan-ignore-next-line https://github.com/larastan/larastan/issues/1774
        return $user->animeList()->where('anime_id', $animeId)->first()?->pivot;
    }

    public function addAnime(User $user, string $animeId): void
    {
        $user->animeList()->attach($animeId, ['status' => StatusEnum::PLAN_TO_WATCH]);
    }

    public function updateAnime(UpdateAnimeInListDTO $dto): void
    {
        $dto->user->animeList()->updateExistingPivot($dto->animeId, $dto->toArray());
    }

    public function removeAnime(User $user, string $animeId): void
    {
        $user->animeList()->detach($animeId);
    }
}
