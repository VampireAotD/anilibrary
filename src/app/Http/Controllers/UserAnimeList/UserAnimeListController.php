<?php

declare(strict_types=1);

namespace App\Http\Controllers\UserAnimeList;

use App\DTO\Service\UserAnimeList\UpdateAnimeInListDTO;
use App\Enums\UserAnimeList\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAnimeList\AddAnimeToListRequest;
use App\Http\Requests\UserAnimeList\UpdateAnimeInListRequest;
use App\Models\Anime;
use App\Services\User\UserAnimeListService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class UserAnimeListController extends Controller
{
    public function __construct(private readonly UserAnimeListService $userAnimeListService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddAnimeToListRequest $request): RedirectResponse
    {
        $this->userAnimeListService->addAnime($request->user(), $request->get('anime_id'));

        return back()->with(['message' => __('user-anime-list.added')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnimeInListRequest $request, Anime $anime): RedirectResponse
    {
        Gate::authorize('updateInList', $anime);

        $this->userAnimeListService->updateAnime(
            new UpdateAnimeInListDTO(
                user    : $request->user(),
                animeId : $anime->id,
                status  : $request->enum('status', StatusEnum::class),
                rating  : $request->get('rating'),
                episodes: $request->get('episodes'),
            )
        );

        return back()->with(['message' => __('user-anime-list.updated')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Anime $anime): RedirectResponse
    {
        Gate::authorize('removeFromList', $anime);

        $this->userAnimeListService->removeAnime($request->user(), $anime->id);

        return back()->with(['message' => __('user-anime-list.removed')]);
    }
}
