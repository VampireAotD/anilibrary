<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase\Scraper;

use App\Enums\AnimeStatusEnum;
use App\Enums\Validation\Scraper\EncodedImageRuleEnum;
use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use App\UseCase\Scraper\AnimeUseCase;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateMocks;

class AnimeUseCaseTest extends TestCase
{
    use RefreshDatabase,
        WithFaker,
        CanCreateMocks,
        CanCreateFakeData;

    private const SCRAPER_ENDPOINT = '/api/v1/anime/parse';

    private AnimeUseCase $animeUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeCloudinary();

        $this->animeUseCase = $this->app->make(AnimeUseCase::class);
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

    public function testCannotCreateAnimeWithoutTitle(): void
    {
        Http::fake(
            [
                self::SCRAPER_ENDPOINT => [
                    'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes' => (string) $this->faker->randomNumber(),
                    'rating'   => $this->faker->randomFloat(),
                ],
            ]
        );

        $this->expectException(ValidationException::class);
        $this->animeUseCase->scrapeAndCreateAnime($this->faker->url);
    }

    /**
     * @dataProvider invalidImageProvider
     * @param string $invalidImage
     * @return void
     */
    public function testCannotCreateAnimeWithInvalidImage(string $invalidImage): void
    {
        Http::fake(
            [
                self::SCRAPER_ENDPOINT => [
                    'title'    => $this->faker->sentence,
                    'image'    => $invalidImage,
                    'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes' => (string) $this->faker->randomNumber(),
                    'rating'   => $this->faker->randomFloat(),
                ],
            ]
        );

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(EncodedImageRuleEnum::INVALID_ENCODING->value);
        $this->animeUseCase->scrapeAndCreateAnime($this->faker->url);
    }

    /**
     * @return void
     */
    public function testCanFindAnimeByTitleAndSynonymsAfterScrapeRequest(): void
    {
        /** @var Anime $anime */
        $anime = $this->createRandomAnimeWithRelations()->first();

        $this->assertCount(1, $anime->urls);
        $this->assertCount(1, $anime->synonyms);

        $newSynonyms = AnimeSynonym::factory(4)->make()->pluck('synonym')->toArray();

        Http::fake(
            [
                self::SCRAPER_ENDPOINT => [
                    'title'    => $this->faker->sentence,
                    'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes' => $this->faker->randomAscii,
                    'rating'   => $this->faker->randomFloat(),
                    'synonyms' => array_merge(
                        $anime->synonyms->pluck('synonym')->toArray(),
                        $newSynonyms
                    ),
                ],
            ]
        );

        $url        = $this->faker->url;
        $foundAnime = $this->animeUseCase->scrapeAndCreateAnime($url);

        $foundAnime->refresh(); // to load upserted relation

        $this->assertEquals($anime->id, $foundAnime->id);
        $this->assertEquals($anime->title, $foundAnime->title);
        $this->assertEquals($anime->status, $foundAnime->status);
        $this->assertEquals($anime->episodes, $foundAnime->episodes);
        $this->assertEquals($anime->image, $foundAnime->image);
        $this->assertEquals($anime->genres->toArray(), $foundAnime->genres->toArray());
        $this->assertEquals($anime->voiceActing->toArray(), $foundAnime->voiceActing->toArray());

        $this->assertCount(2, $foundAnime->urls);
        $this->assertTrue($foundAnime->urls->intersect($anime->urls)->isNotEmpty());
        $this->assertContainsEquals($url, $foundAnime->urls->pluck('url'));

        $this->assertCount(5, $foundAnime->synonyms);
        $this->assertTrue($foundAnime->synonyms->intersect($anime->synonyms)->isNotEmpty());
    }

    /**
     * @return void
     */
    public function testCanCreateAnime(): void
    {
        Cloudinary::shouldReceive('uploadFile')->andReturnSelf();
        Cloudinary::shouldReceive('getSecurePath')->andReturn($this->faker->imageUrl);

        Http::fake(
            [
                self::SCRAPER_ENDPOINT => [
                    'title'       => $this->faker->sentence,
                    'image'       => config('cloudinary.default_image'),
                    'status'      => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes'    => $this->faker->randomAscii,
                    'rating'      => $this->faker->randomFloat(),
                    'genres'      => Genre::factory(5)->make()->pluck('name')->toArray(),
                    'voiceActing' => VoiceActing::factory(5)->make()->pluck('name')->toArray(),
                    'synonyms'    => AnimeSynonym::factory(5)->make()->pluck('synonym')->toArray(),
                ],
            ]
        );

        Bus::fake();

        $url   = $this->faker->url;
        $anime = $this->animeUseCase->scrapeAndCreateAnime($url);

        $anime->refresh(); // to load upserted relation

        $this->assertIsString($anime->id);
        $this->assertNotEmpty($anime->title);
        $this->assertContainsEquals($anime->status, AnimeStatusEnum::values());
        $this->assertNotEmpty($anime->episodes);

        $this->assertNotNull($anime->image);
        $this->assertInstanceOf(Image::class, $anime->image);
        $this->assertNotEmpty($anime->image->path);
        $this->assertNotEmpty($anime->image->alias);

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
        $this->assertNotEmpty($anime->synonyms->first()->synonym);

        Bus::assertDispatched(UpsertAnimeJob::class);
    }
}
