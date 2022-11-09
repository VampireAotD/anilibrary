<?php

declare(strict_types=1);

namespace Tests\Feature\UseCase;

use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Enums\Telegram\AnimeStatusEnum;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\UseCase\AnimeUseCase;
use Database\Seeders\TagSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;

class AnimeUseCaseTest extends TestCase
{
    use RefreshDatabase,
        WithFaker,
        CanCreateMocks;

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
     * @return void
     */
    public function testCannotCreateAnimeWithInvalidImage(): void
    {
        $cases = [Str::random(), 'data:text/html;base64,' . Str::random()];

        foreach ($cases as $case) {
            $this->expectException(InvalidScrapedDataException::class);
            $this->animeUseCase->createAnime(
                new ScrapedDataDTO(
                    $this->faker->url,
                    $this->faker->randomElement(AnimeStatusEnum::values()),
                    (string) $this->faker->randomNumber(),
                    $this->faker->randomFloat(),
                    image: $case
                )
            );
        }
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
                $this->faker->randomElements(),
                $this->faker->randomElements(),
                config('cloudinary.default_image'),
            )
        );

        $anime->load('image');

        $this->assertIsString($anime->id);
        $this->assertNotEmpty($anime->title);
        $this->assertContainsEquals($anime->status, AnimeStatusEnum::values());
        $this->assertNotNull($anime->image);
        $this->assertNotNull($anime->voiceActing);
        $this->assertNotNull($anime->genres);
        $this->assertNotNull($anime->tags);
    }
}
