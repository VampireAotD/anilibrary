<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Anime;

use App\Enums\AnimeStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class AnimeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;
    use CanCreateFakeUsers;

    public function testCannotInteractWithAnimeIfUserIsNotLoggedIn(): void
    {
        $this->get(route('anime.index'))->assertRedirect('/login');
    }

    public function testCannotInteractWithAnimeIfUserIsNotVerified(): void
    {
        $user = $this->createUnverifiedUser();
        $this->actingAs($user)->get(route('anime.index'))->assertRedirect('/verify-email');
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
                                         fn(Assert $page) => $page->has('image')->etc()
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

    public function testUserCanViewAnimeDetails(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnimeWithRelations();

        $this->actingAs($user)->get(route('anime.show', ['anime' => $anime->id]))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Show')
                                     ->has('anime')
                                     ->has('anime.image')
                                     ->has('anime.urls')
                                     ->has('anime.synonyms')
                                     ->has('anime.voice_acting')
                                     ->has('anime.genres')
        );
    }

    public function testCanUpdateAnime(): void
    {
        $user  = $this->createUser();
        $anime = $this->createAnimeWithRelations();

        $this->assertEquals(1, $anime->urls->count());
        $this->assertEquals(1, $anime->synonyms->count());

        // For updating anime upsertRelated is used, so even if the same data will be sent multiple times
        // they will be created only one time, other times they will be just updated
        $urls     = array_merge($anime->urls->pluck('url')->toArray(), [$url = fake()->url, $url, $url]);
        $synonyms = array_merge(
            $anime->synonyms->pluck('synonym')->toArray(),
            [$synonym = fake()->name, $synonym, $synonym]
        );

        $this->actingAs($user)->put(
            route('anime.update', [$anime->id]),
            [
                'title'        => $anime->title,
                'status'       => $this->faker->randomElement(AnimeStatusEnum::values()),
                'episodes'     => $anime->episodes,
                'rating'       => $this->faker->randomNumber(),
                'urls'         => $urls,
                'synonyms'     => $synonyms,
                'voice_acting' => $anime->voiceActing->pluck('id')->toArray(),
                'genres'       => $anime->genres->pluck('id')->toArray(),
            ]
        )->assertOk();

        $anime->refresh();

        $this->assertEquals(2, $anime->urls->count());
        $this->assertEquals(2, $anime->synonyms->count());
    }
}