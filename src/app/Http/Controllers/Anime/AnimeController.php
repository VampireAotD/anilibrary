<?php

declare(strict_types=1);

namespace App\Http\Controllers\Anime;

use App\DTO\Service\Anime\UpdateAnimeDTO;
use App\DTO\Service\Elasticsearch\Anime\AnimePaginationDTO;
use App\Enums\Anime\StatusEnum;
use App\Enums\UserAnimeList\StatusEnum as AnimeListStatusEnum;
use App\Filters\ColumnFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anime\CreateRequest;
use App\Http\Requests\Anime\IndexRequest;
use App\Http\Requests\Anime\UpdateRequest;
use App\Jobs\Scraper\ScrapeAnimeJob;
use App\Models\Anime;
use App\Services\Anime\AnimeService;
use App\Services\Elasticsearch\Index\AnimeIndexService;
use App\Services\Genre\GenreService;
use App\Services\User\UserAnimeListService;
use App\Services\VoiceActing\VoiceActingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class AnimeController extends Controller
{
    public function __construct(
        private readonly AnimeService         $animeService,
        private readonly GenreService         $genreService,
        private readonly VoiceActingService   $voiceActingService,
        private readonly AnimeIndexService    $animeIndexService,
        private readonly UserAnimeListService $userAnimeListService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): Response
    {
        return Inertia::render('Anime/Index', [
            'items' => Inertia::defer(fn() => $this->animeIndexService->paginate(
                new AnimePaginationDTO(
                    page   : $request->integer('page', 1),
                    perPage: $request->integer('perPage', 20),
                    filters: $request->get('filters', []),
                    sort   : $request->get('sort', [])
                )
            )),
            'filters' => Inertia::defer(fn() => $this->animeIndexService->getFacets()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $statuses    = StatusEnum::values();
        $genres      = $this->genreService->all([new ColumnFilter(['name'])])->pluck('name')->toArray();
        $voiceActing = $this->voiceActingService->all([new ColumnFilter(['name'])])->pluck('name')->toArray();

        return Inertia::render('Anime/Create', compact('statuses', 'genres', 'voiceActing'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        // For now, you can only add new anime with scraper
        ScrapeAnimeJob::dispatch($request->user()->id, $request->get('url', ''));

        return back()->with(['message' => __('Request sent to scraper, you will receive notification about results')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Anime $anime): Response
    {
        return Inertia::render('Anime/Show', [
            'anime' => $anime->load([
                'image:id,path',
                'urls:anime_id,url',
                'synonyms:anime_id,name',
                'voiceActing:name',
                'genres:name',
            ]),
            'animeListStatuses' => AnimeListStatusEnum::labels(),
            'animeListEntry'    => Inertia::defer(
                fn() => $this->userAnimeListService->findById($request->user(), $anime->id)
            ),
            'animeListStatistic' => Inertia::defer(fn() => $this->userAnimeListService->animeStatistics($anime->id)),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Anime $anime): RedirectResponse
    {
        try {
            $this->animeService->update($anime, UpdateAnimeDTO::fromArray($request->validated()));

            return to_route('anime.show', $anime->id);
        } catch (Throwable $throwable) {
            Log::error('Updating anime', [
                'exception_trace'   => $throwable->getTraceAsString(),
                'exception_message' => $throwable->getMessage(),
            ]);

            return back()->withErrors(['message' => $throwable->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime): void
    {
        //
    }
}
