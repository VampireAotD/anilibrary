<?php

declare(strict_types=1);

namespace App\Http\Controllers\Anime;

use App\Http\Controllers\Controller;
use App\Services\Anime\AnimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class RandomAnimeController extends Controller
{
    public function __construct(private readonly AnimeService $animeService)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $anime = $this->animeService->randomAnime();

        return to_route('anime.show', $anime->id);
    }
}
