<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Scraper;

use App\Enums\Anime\StatusEnum;
use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Jobs\Image\UploadJob;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\VoiceActing;
use App\Services\Scraper\Client;
use App\UseCase\Scraper\ScraperUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

class ScraperUseCaseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;
    use CanCreateMocks;

    private ScraperUseCase $scraperUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scraperUseCase = $this->app->make(ScraperUseCase::class);
    }

    /**
     * @return array<array<string>>
     */
    public static function invalidImageProvider(): array
    {
        return [
            [Str::random()],
            ['data:text/html;base64,' . Str::random()],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function validImageProvider(): array
    {
        return [
            [sprintf('data:image/jpeg;base64,%s', Str::random())],
            [sprintf('data:image/jpg;base64,%s', Str::random())],
            [sprintf('data:image/png;base64,%s', Str::random())],
            [sprintf('data:image/gif;base64,%s', Str::random())],
            [sprintf('data:image/webp;base64,%s', Str::random())],
        ];
    }

    public function testCannotScrapeIfServiceIsDown(): void
    {
        Http::fake([
            Client::SCRAPE_ENDPOINT => [],
        ]);

        $this->expectException(ConnectionException::class);
        $this->scraperUseCase->scrapeByUrl($this->faker->url);
    }

    public function testCannotScrapeIfServiceIsUnavailable(): void
    {
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response(status: Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $this->expectException(RequestException::class);
        $this->scraperUseCase->scrapeByUrl($this->faker->url);
    }

    public function testCannotCreateAnimeWithoutTitle(): void
    {
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'year'     => $this->faker->year,
                'type'     => $this->faker->randomAnimeType(),
                'status'   => $this->faker->randomAnimeStatus(),
                'episodes' => $this->faker->randomAnimeEpisodes(),
                'rating'   => $this->faker->randomAnimeRating(),
            ]),
        ]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The title field is required.');
        $this->scraperUseCase->scrapeByUrl($this->faker->url);
    }

    #[DataProvider('invalidImageProvider')]
    public function testCannotCreateAnimeWithInvalidImage(string $invalidImage): void
    {
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'    => $this->faker->sentence,
                'image'    => $invalidImage,
                'year'     => $this->faker->year,
                'type'     => $this->faker->randomAnimeType(),
                'status'   => $this->faker->randomAnimeStatus(),
                'episodes' => $this->faker->randomAnimeEpisodes(),
                'rating'   => $this->faker->randomAnimeRating(),
            ]),
        ]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(__('validation.scraper.image', ['attribute' => 'image']));
        $this->scraperUseCase->scrapeByUrl($this->faker->url);
    }

    public function testCanFindSimilarAnimeAfterScrapeRequest(): void
    {
        $anime = $this->createAnimeWithRelations();

        // Ensure that anime already has relations
        $this->assertCount(1, $anime->urls);
        $this->assertCount(1, $anime->synonyms);

        // Create new synonyms that scraper will return
        $newSynonyms = AnimeSynonym::factory(4)->make()->toArray();

        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'    => $this->faker->sentence,
                'year'     => $anime->year,
                'type'     => $anime->type,
                'status'   => $status = $this->faker->randomAnimeStatus(),
                'episodes' => $episodes = $this->faker->randomAnimeEpisodes(),
                'rating'   => $rating = $this->faker->randomAnimeRating(),
                'synonyms' => array_merge($anime->synonyms->select('name')->toArray(), $newSynonyms),
            ]),
        ]);

        $url        = $this->faker->url;
        $foundAnime = $this->scraperUseCase->scrapeByUrl($url);

        $foundAnime->refresh(); // to reload relations

        $this->assertEquals($anime->id, $foundAnime->id);
        $this->assertEquals($anime->title, $foundAnime->title);
        $this->assertEquals($anime->image, $foundAnime->image);
        $this->assertEquals($anime->genres->toArray(), $foundAnime->genres->toArray());
        $this->assertEquals($anime->voiceActing->toArray(), $foundAnime->voiceActing->toArray());

        // Ensure that only status, episodes and rating have been updated
        $this->assertEquals($foundAnime->status, $status);
        $this->assertEquals($foundAnime->episodes, $episodes);
        $this->assertEquals($foundAnime->rating, $rating);

        // Ensure that new relations have been created
        $this->assertCount(2, $foundAnime->urls);
        $this->assertTrue($foundAnime->urls->intersect($anime->urls)->isNotEmpty());
        $this->assertContainsEquals($url, $foundAnime->urls->pluck('url'));

        $this->assertCount(5, $foundAnime->synonyms);
        $this->assertTrue($foundAnime->synonyms->intersect($anime->synonyms)->isNotEmpty());
    }

    public function testWillNotUpdateImageIfAnimeAlreadyHasOne(): void
    {
        $anime = $this->createAnimeWithRelations();

        $this->assertNotNull($anime->image);

        Bus::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'    => $anime->title,
                'image'    => $this->faker->randomAnimeImage(),
                'year'     => $anime->year,
                'type'     => $anime->type,
                'status'   => $this->faker->randomAnimeStatus(),
                'episodes' => $this->faker->randomAnimeEpisodes(),
                'rating'   => $this->faker->randomAnimeRating(),
            ]),
        ]);

        $foundAnime = $this->scraperUseCase->scrapeByUrl($this->faker->url);

        Bus::assertNotDispatched(UploadJob::class);

        $this->assertEquals($anime->image, $foundAnime->image);
    }

    #[DataProvider('validImageProvider')]
    public function testCanCreateAnime(string $image): void
    {
        Bus::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'       => $this->faker->sentence,
                'image'       => $image,
                'year'        => $this->faker->year,
                'type'        => $this->faker->randomAnimeType(),
                'status'      => $this->faker->randomAnimeStatus(),
                'episodes'    => $this->faker->randomAnimeEpisodes(),
                'rating'      => $this->faker->randomAnimeRating(),
                'genres'      => Genre::factory(5)->make()->toArray(),
                'voiceActing' => VoiceActing::factory(5)->make()->toArray(),
                'synonyms'    => AnimeSynonym::factory(5)->make()->toArray(),
            ]),
        ]);

        $url   = $this->faker->url;
        $anime = $this->scraperUseCase->scrapeByUrl($url);

        $anime->refresh(); // to reload relations

        $this->assertIsString($anime->id);
        $this->assertNotEmpty($anime->title);
        $this->assertContainsEquals($anime->status, StatusEnum::cases());
        $this->assertNotEmpty($anime->episodes);

        Bus::assertDispatched(UploadJob::class, function (UploadJob $job) use ($image) {
            return $job->image === $image;
        });

        $this->assertTrue($anime->voiceActing->isNotEmpty());
        $this->assertInstanceOf(VoiceActing::class, $anime->voiceActing->first());
        $this->assertNotEmpty($anime->voiceActing->first()->name);

        $this->assertTrue($anime->genres->isNotEmpty());
        $this->assertInstanceOf(Genre::class, $anime->genres->first());
        $this->assertNotEmpty($anime->genres->first()->name);

        $this->assertTrue($anime->urls->isNotEmpty());
        $this->assertInstanceOf(AnimeUrl::class, $anime->urls->first());
        $this->assertNotEmpty($anime->urls->first()->url);
        $this->assertContainsEquals($url, $anime->urls->pluck('url'));

        $this->assertTrue($anime->synonyms->isNotEmpty());
        $this->assertInstanceOf(AnimeSynonym::class, $anime->synonyms->first());
        $this->assertNotEmpty($anime->synonyms->first()->name);

        Bus::assertDispatched(UpsertAnimeJob::class);
    }
}
