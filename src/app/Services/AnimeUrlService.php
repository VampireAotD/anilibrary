<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AnimeUrl\AnimeUrlRepositoryInterface;

final readonly class AnimeUrlService
{
    public function __construct(private AnimeUrlRepositoryInterface $animeUrlRepository)
    {
    }

    public function countAnimePerDomain(): array
    {
        return $this->animeUrlRepository->countAnimePerDomain();
    }
}
