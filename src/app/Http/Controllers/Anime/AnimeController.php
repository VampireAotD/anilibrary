<?php

declare(strict_types=1);

namespace App\Http\Controllers\Anime;

use App\DTO\Service\Anime\UpdateAnimeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Anime\IndexRequest;
use App\Http\Requests\Anime\UpdateRequest;
use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Repositories\Filters\PaginationFilter;
use App\Services\AnimeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AnimeController extends Controller
{
    public function __construct(
        private readonly AnimeRepositoryInterface $animeRepository,
        private readonly AnimeService             $animeService
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
        $pagination = $this->animeRepository->paginate($filter->withRelations('urls', 'image'));

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Anime $anime)
    {
        //
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
            logger()->error(
                'Updating anime',
                [
                    'exception_trace'   => $exception->getTraceAsString(),
                    'exception_message' => $exception->getMessage(),
                ]
            );

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
