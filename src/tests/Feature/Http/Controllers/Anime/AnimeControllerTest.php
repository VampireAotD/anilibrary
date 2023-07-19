<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Anime;

use App\Enums\AnimeStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class AnimeControllerTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;
    use CanCreateFakeUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function testCannotInteractWithAnimeIfUserIsNotLoggedIn(): void
    {
        $this->get(route('anime.index'))->assertRedirect('/login');
    }

    public function testCanViewIndexPageWithPagination(): void
    {
        $user = $this->createUser();
        $this->createAnimeCollectionWithRelations(20);

        $this->actingAs($user)->get(route('anime.index'))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
                                     ->has('pagination')
                                     ->has('pagination.next_page_url')
                                     ->has('pagination.data', 20)
                                     ->has(
                                         'pagination.data.0',
                                         fn(Assert $page) => $page->has('image')
                                                                  ->has('urls')
                                                                  ->etc()
                                     )
        );
    }

    public function testCanChangePaginationPageOnIndexPage(): void
    {
        $user = $this->createUser();
        $this->createAnimeCollectionWithRelations(30);

        $this->actingAs($user)->get(route('anime.index', ['page' => 2]))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
                                     ->has('pagination')
                                     ->has('pagination.prev_page_url')
                                     ->has('pagination.data', 10)
        );
    }

    public function testCanChangeShownAnimeQuantityOnIndexPage(): void
    {
        $user = $this->createUser();
        $this->createAnimeCollectionWithRelations(30);

        $this->actingAs($user)->get(route('anime.index', ['per_page' => 30]))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
                                     ->has('pagination')
                                     ->has('pagination.data', 30)
        );
    }

    public function testCanUpdateAnime(): void
    {
        $user     = $this->createUser();
        $anime    = $this->createAnimeWithRelations();
        $urls     = array_merge($anime->urls->pluck('url')->toArray(), [$url = fake()->url, $url]);
        $synonyms = array_merge($anime->synonyms->pluck('synonym')->toArray(), [fake()->name]);

        $response = $this->actingAs($user)->put(
            route('anime.update', [$anime->id]),
            [
                'title'        => $anime->title . '11111111',
                'status'       => AnimeStatusEnum::ANNOUNCE->value,
                'episodes'     => $anime->episodes,
                'rating'       => $anime->rating,
                'urls'         => $urls,
                'synonyms'     => $synonyms,
                'voice_acting' => $anime->voiceActing->pluck('id')->toArray(),
                'genres'       => $anime->genres->pluck('id')->toArray(),
            ]
        )->assertOk();

        $anime->refresh();
    }
}
