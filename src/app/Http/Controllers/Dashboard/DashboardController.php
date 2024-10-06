<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\AnimeService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly AnimeService $animeService)
    {
    }

    public function index(): Response
    {
        $latestAnime      = $this->animeService->getTenLatestAnime();
        $completedAnime   = $this->animeService->getTenLatestCompletedAnime();
        $mostPopularAnime = $this->animeService->getTenMostPopularAnime();

        return Inertia::render('Dashboard/Index', compact('latestAnime', 'completedAnime', 'mostPopularAnime'));
    }
}
