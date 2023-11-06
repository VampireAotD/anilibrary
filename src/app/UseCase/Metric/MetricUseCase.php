<?php

declare(strict_types=1);

namespace App\UseCase\Metric;

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

    public function getAnimeMetrics(): array
    {
        // TODO add cache decorator for repositories
        return [
            'animeCount'     => $this->animeService->countAnime(),
            'usersCount'     => $this->userService->countUsers(),
            'animePerMonth'  => $this->animeService->getParsedAnimePerMonth(),
            'animePerDomain' => $this->animeUrlService->countAnimePerDomain(),
            'latestAnime'    => $this->animeService->getTenLatestAnime(),
        ];
    }
}
