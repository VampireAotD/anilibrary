<?php

declare(strict_types=1);

namespace App\UseCase\Metric;

use App\DTO\UseCase\Metric\MetricDTO;
use App\Services\AnimeService;
use App\Services\AnimeUrlService;
use App\Services\UserService;

/**
 * Class MetricUseCase
 * @package App\UseCase\Metric
 */
readonly class MetricUseCase
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
            $this->userService->countUsers(),
            $this->animeService->getParsedAnimePerMonth(),
            $this->animeUrlService->countAnimePerDomain(),
            $this->animeService->getTenLatestAnime(),
        );
    }
}
