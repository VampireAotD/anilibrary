<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function __construct(private readonly AnimeService $animeService)
    {
    }

    public function __invoke(): Response
    {
        return Inertia::render('Dashboard/Index', [
            'latestAnime'      => $this->animeService->getTenLatestAnime(),
            'completedAnime'   => Inertia::defer(fn() => $this->animeService->getTenLatestReleasedAnime(), 'lists'),
            'mostPopularAnime' => Inertia::defer(fn() => $this->animeService->getTenMostPopularAnime(), 'lists'),
        ]);
    }
}
