<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\UseCase\Metric\MetricUseCase;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly MetricUseCase $metricUseCase)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Dashboard/Dashboard', $this->metricUseCase->getAnimeMetrics());
    }
}
