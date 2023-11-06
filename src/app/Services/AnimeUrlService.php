<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AnimeUrl\AnimeUrlRepositoryInterface;

/**
 * Class AnimeUrlService
 * @package App\Services
 */
readonly class AnimeUrlService
{
    public function __construct(private AnimeUrlRepositoryInterface $animeUrlRepository)
    {
    }

    public function countAnimePerDomain(): array
    {
        return $this->animeUrlRepository->countAnimePerDomain();
    }
}
