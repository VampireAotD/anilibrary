<?php

declare(strict_types=1);

namespace App\Http\Controllers\Anime;

use App\DTO\Service\Anime\UpdateAnimeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anime\CreateRequest;
use App\Http\Requests\Anime\IndexRequest;
use App\Http\Requests\Anime\UpdateRequest;
use App\Jobs\Scraper\ScrapeAnimeJob;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Repositories\Filters\PaginationFilter;
use App\Services\AnimeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AnimeController extends Controller
{
    public function __construct(
        private readonly AnimeRepositoryInterface $animeRepository,
        private readonly AnimeService             $animeService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): Response
    {
        $page    = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 20);

        $filter     = new PaginationFilter($page, $perPage);
        $pagination = $this->animeRepository->paginate(
            $filter->withColumns('id', 'title', 'episodes', 'rating', 'status')
                   ->withRelations('image:model_id,path')
        );

        return Inertia::render('Anime/Index', compact('pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            'synonyms:anime_id,synonym',
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
    public function update(UpdateRequest $request, Anime $anime): Response
    {
        $dto = UpdateAnimeDTO::fromArray($request->validated());

        try {
            $anime = $this->animeService->update($anime, $dto);

            return Inertia::render('', compact('anime'));
        } catch (Throwable $exception) {
            logger()->error('Updating anime', [
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);

            $errors = [$exception->getMessage()];

            return Inertia::render('', compact('anime', 'errors'));
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
