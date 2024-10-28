<?php

declare(strict_types=1);

namespace App\Services\Anime;

use App\Models\AnimeUrl;

final readonly class AnimeUrlService
{
    /**
     * @return array<string, int>
     */
    public function countAnimePerDomain(): array
    {
        return AnimeUrl::query()
                       ->selectRaw("SUBSTRING_INDEX(url, '/', 3) as domain, COUNT(url) as anime_per_domain")
                       ->groupBy('domain')
                       ->pluck('anime_per_domain', 'domain')
                       ->toArray();
    }
}
