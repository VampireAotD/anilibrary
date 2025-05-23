<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Anime;

use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Jobs\Scraper\ScrapeAnimeJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeElasticClient;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class AnimeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;
    use CanCreateFakeUsers;
    use CanCreateFakeElasticClient;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeElasticsearchClient();
    }

    public function testCannotInteractWithAnimeIfUserIsNotLoggedIn(): void
    {
        $this->get(route('anime.index'))->assertRedirect('/login');
    }

    public function testCannotInteractWithAnimeIfUserIsNotVerified(): void
    {
        $user = $this->createUnverifiedUser();
        $this->actingAs($user)->get(route('anime.index'))->assertRedirectToRoute('verification.notice');
    }

    public function testCanViewIndexPageWithPagination(): void
    {
        $user = $this->createUser();

        $this->elasticHandler->append($this->createElasticResponseForAnimeWithRelations(20));
        $this->elasticHandler->append($this->createElasticResponseForAnimeFacets());

        // TODO Assert other props if Inertia will support deferred props
        $this->actingAs($user)->get(route('anime.index'))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
        );
    }

    public function testCanChangePaginationPageOnIndexPage(): void
    {
        $user = $this->createUser();

        $this->elasticHandler->append($this->createElasticResponseForAnimeWithRelations(20));
        $this->elasticHandler->append($this->createElasticResponseForAnimeFacets());

        // TODO Assert other props if Inertia will support deferred props
        $this->actingAs($user)->get(route('anime.index'))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
        );

        $this->elasticHandler->append($this->createElasticResponseForAnimeWithRelations(10));
        $this->elasticHandler->append($this->createElasticResponseForAnimeFacets());

        // TODO Assert other props if Inertia will support deferred props
        $this->actingAs($user)->get(route('anime.index', ['page' => 2]))->assertInertia(
            fn(Assert $page) => $page->component('Anime/Index')
        );
    }

    public function testUserCanCreateAnimeBySendingScrapeRequest(): void
    {
        Bus::fake();

        $url = 'https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129';

        $this->actingAs($this->createUser())->post(route('anime.store'), compact('url'))->assertRedirect();

        Bus::assertDispatched(ScrapeAnimeJob::class);
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

    public function testUserCanUpdateAnime(): void
    {
        Bus::fake();

        $user  = $this->createUser();
        $anime = $this->createAnimeWithRelations();

        $this->assertEquals(1, $anime->urls->count());
        $this->assertEquals(1, $anime->synonyms->count());

        $generatedUrls = [
            [
                'url' => $url = $this->faker->url,
            ],
            [
                'url' => $url,
            ],
            [
                'url' => $url,
            ],
        ];

        $generatedSynonyms = [
            [
                'name' => $synonym = $this->faker->name,
            ],
            [
                'name' => $synonym,
            ],
            [
                'name' => $synonym,
            ],
        ];

        // For updating anime upsertRelated is used, so even if the same data will be sent multiple times
        // they will be created only one time, other times they will be just updated
        $urls     = array_merge($anime->urls->select('url')->toArray(), $generatedUrls);
        $synonyms = array_merge($anime->synonyms->select('name')->toArray(), $generatedSynonyms);

        $this->actingAs($user)->put(route('anime.update', [$anime->id]), [
            'title'        => $anime->title,
            'type'         => $this->faker->randomAnimeType()->value,
            'year'         => $this->faker->year,
            'status'       => $this->faker->randomAnimeStatus()->value,
            'episodes'     => $anime->episodes,
            'rating'       => $this->faker->randomAnimeRating(),
            'urls'         => $urls,
            'synonyms'     => $synonyms,
            'voice_acting' => $anime->voiceActing->pluck('id')->toArray(),
            'genres'       => $anime->genres->pluck('id')->toArray(),
        ])->assertRedirectToRoute('anime.show', $anime->id);

        $anime->refresh();

        Bus::assertDispatched(UpsertAnimeJob::class); // because anime is updated
        $this->assertEquals(2, $anime->urls->count());
        $this->assertEquals(2, $anime->synonyms->count());
    }
}
