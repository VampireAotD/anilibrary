<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Scraper;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Jobs\Image\UploadJob;
use App\Models\AnimeSynonym;
use App\Models\Genre;
use App\Models\VoiceActing;
use App\Services\Scraper\Client;
use App\UseCase\Scraper\ScraperUseCase;
use Http\Promise\RejectedPromise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

class ScraperUseCaseTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;

    private ScraperUseCase $scraperUseCase;

    #[\Override]
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
            Client::SCRAPE_ENDPOINT => fn(Request $request) => new RejectedPromise(
                throw new ConnectionException('testing'),
            ),
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

    #[DataProvider('validImageProvider')]
    public function testCanCreateAnime(string $image): void
    {
        $genres      = Genre::factory(count: 5)->make();
        $synonyms    = AnimeSynonym::factory(count: 5)->make();
        $voiceActing = VoiceActing::factory(count: 5)->make();

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
                'synonyms'    => $synonyms->select('name')->toArray(),
                'voiceActing' => $voiceActing->select('name')->toArray(),
                'genres'      => $genres->select('name')->toArray(),
            ]),
        ]);

        $url   = $this->faker->url;
        $anime = $this->scraperUseCase->scrapeByUrl($url);

        Bus::assertDispatched(UpsertAnimeJob::class);
        Bus::assertDispatched(UploadJob::class, static fn(UploadJob $job) => $job->image === $image);

        $this->assertTrue(Str::isUuid($anime->id));
        $this->assertNotEmpty($anime->title);
        $this->assertContainsEquals($anime->type, TypeEnum::cases());
        $this->assertContainsEquals($anime->status, StatusEnum::cases());
        $this->assertIsInt($anime->episodes);
        $this->assertIsFloat($anime->rating);
        $this->assertIsInt($anime->year);

        $this->assertNotEmpty($anime->image);
        $this->assertContainsEquals($url, $anime->urls->pluck('url'));
        $this->assertNotEmpty($anime->genres->pluck('name')->intersect($genres->pluck('name')));
        $this->assertNotEmpty($anime->synonyms->pluck('name')->intersect($synonyms->pluck('name')));
        $this->assertNotEmpty($anime->voiceActing->pluck('name')->intersect($voiceActing->pluck('name')));
    }

    public function testCanFindAndUpdateSimilarAnime(): void
    {
        $anime = $this->createAnimeWithRelations();

        // Ensure that anime already has relations
        $this->assertCount(1, $anime->urls);
        $this->assertCount(1, $anime->synonyms);

        $genres      = Genre::factory(count: 4)->make();
        $synonyms    = AnimeSynonym::factory(count: 4)->make();
        $voiceActing = VoiceActing::factory(count: 4)->make();

        Bus::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'       => $this->faker->sentence,
                'year'        => $anime->year,
                'type'        => $anime->type,
                'status'      => $status   = $this->faker->randomAnimeStatus(),
                'episodes'    => $episodes = $this->faker->randomAnimeEpisodes(),
                'rating'      => $this->faker->randomAnimeRating(),
                'synonyms'    => $anime->synonyms->concat($synonyms)->select('name'),
                'genres'      => $anime->genres->concat($genres)->select('name'),
                'voiceActing' => $anime->voiceActing->concat($voiceActing)->select('name'),
            ]),
        ]);

        $url        = $this->faker->url;
        $foundAnime = $this->scraperUseCase->scrapeByUrl($url)->refresh();

        Bus::assertDispatched(UpsertAnimeJob::class); // because anime is updated

        // Ensure that only status, episodes and relations have been updated
        $this->assertEquals($foundAnime->status, $status);
        $this->assertEquals($foundAnime->episodes, $episodes);

        $this->assertCount(2, $foundAnime->urls);
        $this->assertContainsEquals($url, $foundAnime->urls->pluck('url'));

        $this->assertCount(5, $foundAnime->synonyms);
        $this->assertNotEmpty($foundAnime->synonyms->pluck('name')->intersect($synonyms->pluck('name')));

        $this->assertCount(5, $foundAnime->genres);
        $this->assertNotEmpty($foundAnime->genres->pluck('name')->intersect($genres->pluck('name')));

        $this->assertCount(5, $foundAnime->voiceActing);
        $this->assertNotEmpty($foundAnime->voiceActing->pluck('name')->intersect($voiceActing->pluck('name')));
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
}
