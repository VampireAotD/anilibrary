<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Models\Anime;
use Illuminate\Database\Eloquent\Collection;

interface AnimeStatisticInterface
{
    /**
     * @return Collection<int, Anime>
     */
    public function getLatestAnime(int $limit = 10): Collection;

    /**
     * @return Collection<int, Anime>
     */
    public function getMostPopularAnime(int $limit = 10): Collection;

    /**
     * @return Collection<int, Anime>
     */
    public function getLatestCompletedAnime(int $limit = 10): Collection;
}
