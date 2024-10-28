<?php

declare(strict_types=1);

namespace App\Services\Anime;

use App\Models\AnimeUrl;

final readonly class AnimeUrlService
{
    /**
     * @return array<string, int>
     */
    public function countAnimeByDomain(): array
    {
        return AnimeUrl::countByDomain()->pluck('anime', 'domain')->toArray();
    }
}
