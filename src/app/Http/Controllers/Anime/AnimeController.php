<?php

declare(strict_types=1);

namespace App\Http\Controllers\Anime;

use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\Enums\AnimeStatusEnum;
use App\Filters\ColumnFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anime\CreateRequest;
use App\Http\Requests\Anime\IndexRequest;
use App\Http\Requests\Anime\UpdateRequest;
use App\Jobs\Scraper\ScrapeAnimeJob;
use App\Models\Anime;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\VoiceActingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AnimeController extends Controller
{
    public function __construct(
        private readonly AnimeService       $animeService,
        private readonly GenreService       $genreService,
        private readonly VoiceActingService $voiceActingService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): Response
    {
        $page    = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 20);

        $pagination = $this->animeService->paginate(
            new AnimePaginationDTO(
                $page,
                $perPage,
                [
                    new ColumnFilter(['id', 'title', 'episodes', 'rating', 'status']),
                ]
            )
        );

        return Inertia::render('Anime/Index', compact('pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses    = AnimeStatusEnum::values();
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

        return back()->with(['message' => __('Send request to scraper, you will receive notification about result')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime): Response
    {
        $anime->load([
            'image:model_id,path',
            'urls:anime_id,url',
            'synonyms:anime_id,name',
            'voiceActing:name',
            'genres:name',
        ]);

        return Inertia::render('Anime/Show', compact('anime'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anime $anime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Anime $anime): RedirectResponse
    {
        try {
            $this->animeService->update($anime, UpsertAnimeDTO::fromArray($request->validated()));

            return to_route('anime.show', $anime->id);
        } catch (Throwable $exception) {
            Log::error('Updating anime', [
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);

            return back()->withErrors(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anime $anime)
    {
        //
    }
}
