<?php

declare(strict_types=1);

namespace App\UseCase\Metric;

use App\DTO\UseCase\Metric\MetricDTO;
use App\Services\Anime\AnimeService;
use App\Services\Anime\AnimeUrlService;
use App\Services\User\UserService;

final readonly class MetricUseCase
{
    public function __construct(
        private AnimeService    $animeService,
        private AnimeUrlService $animeUrlService,
        private UserService     $userService,
    ) {
    }

    public function getAnimeMetrics(): MetricDTO
    {
        // TODO add cache decorator for repositories
        return new MetricDTO(
            $this->animeService->countAnime(),
            $this->userService->count(),
            $this->animeService->getParsedAnimePerMonth(),
            $this->animeUrlService->countAnimeByDomain(),
            $this->animeService->getTenLatestAnime(),
        );
    }
}
