<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase;

use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Enums\Telegram\AnimeStatusEnum;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use App\UseCase\AnimeUseCase;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateMocks;

class AnimeUseCaseTest extends TestCase
{
    use RefreshDatabase,
        WithFaker,
        CanCreateMocks,
        CanCreateFakeData;

    private AnimeUseCase $animeUseCase;
    private MockObject   $cloudinaryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TagSeeder::class);

        $this->cloudinaryMock = $this->createCloudinaryMock();
        $this->animeUseCase   = $this->app->make(AnimeUseCase::class);
    }

    /**
     * @return array<array<string>>
     */
    public function invalidImageProvider(): array
    {
        return [
            [Str::random()],
            ['data:text/html;base64,' . Str::random()],
        ];
    }

    /**
     * @return void
     */
    public function testCannotCreateAnimeWithoutTitle(): void
    {
        $this->expectException(InvalidScrapedDataException::class);
        $this->animeUseCase->createAnime(
            new ScrapedDataDTO(
                $this->faker->url,
                $this->faker->randomElement(AnimeStatusEnum::values()),
                (string) $this->faker->randomNumber(),
                $this->faker->randomFloat(),
            )
        );
    }

    /**
     * @dataProvider invalidImageProvider
     * @param string $invalidImage
     * @return void
     */
    public function testCannotCreateAnimeWithInvalidImage(string $invalidImage): void
    {
        $this->expectException(InvalidScrapedDataException::class);
        $this->animeUseCase->createAnime(
            new ScrapedDataDTO(
                $this->faker->url,
                $this->faker->randomElement(AnimeStatusEnum::values()),
                (string) $this->faker->randomNumber(),
                $this->faker->randomFloat(),
                image: $invalidImage
            )
        );
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
                '/api/v1/anime/parse' => [
                    'url'      => $url = $this->faker->url,
                    'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
                    'episodes' => $this->faker->randomAscii,
                    'rating'   => $this->faker->randomFloat(),
                    'title'    => $this->faker->sentence,
                    'synonyms' => array_merge(
                        $anime->synonyms->pluck('synonym')->toArray(),
                        $newSynonyms
                    ),
                ],
            ]
        );

        $dto        = $this->animeUseCase->sendScrapeRequest($url);
        $foundAnime = $this->animeUseCase->createAnime($dto);

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
        $this->assertTrue($foundAnime->urls->contains('url', $url));

        $this->assertCount(5, $foundAnime->synonyms);
        $this->assertTrue($foundAnime->synonyms->intersect($anime->synonyms)->isNotEmpty());
    }

    /**
     * @return void
     */
    public function testCanCreateAnime(): void
    {
        $this->cloudinaryMock->method('uploadFile')->willReturn($this->cloudinaryMock);
        $this->cloudinaryMock->method('getSecurePath')->willReturn($this->faker->imageUrl);

        $anime = $this->animeUseCase->createAnime(
            new ScrapedDataDTO(
                $this->faker->url,
                $this->faker->randomElement(AnimeStatusEnum::values()),
                (string) $this->faker->randomNumber(),
                $this->faker->randomFloat(),
                $this->faker->title,
                Genre::factory(5)->create()->pluck('name')->toArray(),
                VoiceActing::factory(5)->create()->pluck('name')->toArray(),
                AnimeSynonym::factory(5)->make()->pluck('synonym')->toArray(),
                config('cloudinary.default_image')
            )
        );

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

        $this->assertTrue($anime->synonyms->isNotEmpty());
        $this->assertInstanceOf(AnimeSynonym::class, $anime->synonyms->first());
        $this->assertNotEmpty($anime->synonyms->first()->synonym);
    }
}
