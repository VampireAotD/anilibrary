<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\Service\UserAnimeList\UpdateAnimeInListDTO;
use App\Enums\UserAnimeList\StatusEnum;
use App\Models\Pivots\UserAnimeList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class UserAnimeListService
{
    public function findById(User $user, string $animeId): ?UserAnimeList
    {
        // @phpstan-ignore-next-line https://github.com/larastan/larastan/issues/1774
        return $user->animeList()->where('anime_id', $animeId)->first()?->pivot;
    }

    /**
     * @return array{status: string, user_count: int, percentage: float}
     */
    public function animeStatistics(string $animeId): array
    {
        $subQuery = UserAnimeList::query()->fromRaw(
            "(
                SELECT 'plan_to_watch' AS status
                UNION ALL SELECT 'watching'
                UNION ALL SELECT 'on_hold'
                UNION ALL SELECT 'completed'
                UNION ALL SELECT 'dropped'
            ) as statuses"
        );

        // Not using model directly because of Model::shouldBeStrict()
        /** @var array{status: string, user_count: int, percentage: float} */
        return DB::table(UserAnimeList::class)
                 ->fromSub(
                     $subQuery->leftJoin(
                         'user_anime_list as ual',
                         static fn($join) => $join->on(
                             'statuses.status',
                             '=',
                             'ual.status'
                         )->where('ual.anime_id', $animeId)
                     )
                              ->groupBy('statuses.status')
                              ->select([
                                  'statuses.status',
                                  DB::raw('COUNT(ual.user_id) as user_count'),
                                  DB::raw('SUM(COUNT(ual.user_id)) OVER() as total_count'),
                              ]),
                     'sub'
                 )
                 ->select([
                     'status',
                     'user_count',
                     DB::raw('ROUND(user_count * 100 / total_count, 1) as percentage'),
                 ])
                 ->get()
                 ->map(static function (\stdClass $item) {
                     $item->percentage = (float) $item->percentage;
                     return (array) $item;
                 })
                 ->toArray();
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
