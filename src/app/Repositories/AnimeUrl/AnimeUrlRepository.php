<?php

declare(strict_types=1);

namespace App\Repositories\AnimeUrl;

use App\Models\AnimeUrl;
use Illuminate\Database\Eloquent\Builder;

class AnimeUrlRepository implements AnimeUrlRepositoryInterface
{
    /**
     * @var Builder<AnimeUrl>
     */
    protected Builder $query;

    public function __construct()
    {
        $this->query = AnimeUrl::query();
    }

    public function countAnimePerDomain(): array
    {
        return $this->query->selectRaw("SUBSTRING_INDEX(url, '/', 3) as domain, COUNT(url) as anime")
                           ->groupBy('domain')
                           ->pluck('anime', 'domain')
                           ->toArray();
    }
}
