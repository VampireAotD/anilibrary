<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Anime;

use App\DTO\Service\Anime\CreateAnimeDTO;
use App\DTO\Service\Anime\UpdateAnimeDTO;
use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Jobs\Image\UploadJob;
use App\Models\Anime;
use App\Services\Anime\AnimeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

class AnimeServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeAnime;

    private AnimeService $animeService;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->animeService = $this->app->make(AnimeService::class);
    }

    public function testCanCreateAnimeWithImage(): void
    {
        Bus::fake();

        $this->assertDatabaseEmpty(Anime::class);

        $dto = new CreateAnimeDTO(
            title   : $this->faker->title,
            year    : (int) $this->faker->year,
            urls    : [['url' => $this->faker->url]],
            type    : $this->faker->randomAnimeType(),
            status  : $this->faker->randomAnimeStatus(),
            rating  : $this->faker->randomAnimeRating(),
            episodes: $this->faker->randomAnimeEpisodes(),
            image   : $this->faker->randomAnimeImage()
        );

        $anime = $this->animeService->create($dto);

        Bus::assertDispatched(UploadJob::class);

        $this->assertDatabaseCount(Anime::class, 1);
        $this->assertNotNull($anime);
        $this->assertEquals($dto->title, $anime->title);
        $this->assertEquals($dto->type, $anime->type);
        $this->assertEquals($dto->year, $anime->year);
        $this->assertEquals(collect($dto->urls)->pluck('url'), $anime->urls->pluck('url'));
    }

    public function testCanCreateAnimeWithoutImage(): void
    {
        Bus::fake();

        $this->assertDatabaseEmpty(Anime::class);

        $dto = new CreateAnimeDTO(
            title   : $this->faker->title,
            year    : (int) $this->faker->year,
            urls    : [['url' => $this->faker->url]],
            type    : $this->faker->randomAnimeType(),
            status  : $this->faker->randomAnimeStatus(),
            rating  : $this->faker->randomAnimeRating(),
            episodes: $this->faker->randomAnimeEpisodes(),
        );

        $anime = $this->animeService->create($dto);

        Bus::assertNotDispatched(UploadJob::class);

        $this->assertDatabaseCount(Anime::class, 1);
        $this->assertNotNull($anime);
        $this->assertEquals($dto->title, $anime->title);
        $this->assertEquals($dto->type, $anime->type);
        $this->assertEquals($dto->year, $anime->year);
        $this->assertEquals($dto->status, $anime->status);
        $this->assertEquals($dto->rating, $anime->rating);
        $this->assertEquals($dto->episodes, $anime->episodes);
        $this->assertEquals(collect($dto->urls)->pluck('url'), $anime->urls->pluck('url'));
    }

    public function testCanUpdateAnime(): void
    {
        Bus::fake(); // for AnimeObserver

        $anime = $this->createAnime([
            'type'   => TypeEnum::MOVIE,
            'status' => StatusEnum::ONGOING,
        ]);

        $dto = new UpdateAnimeDTO(
            title   : $anime->title,
            type    : TypeEnum::SHOW,
            status  : StatusEnum::RELEASED,
            rating  : $this->faker->randomAnimeRating(),
            episodes: $this->faker->randomAnimeEpisodes(),
            year    : (int) $anime->year,
            urls    : [['url' => $this->faker->url]],
        );

        $this->assertDatabaseCount(Anime::class, 1);

        $updatedAnime = $this->animeService->update($anime, $dto);

        $this->assertDatabaseCount(Anime::class, 1);
        $this->assertNotNull($updatedAnime);
        $this->assertEquals($dto->title, $updatedAnime->title);
        $this->assertEquals($dto->type, $updatedAnime->type);
        $this->assertEquals($dto->year, $updatedAnime->year);
        $this->assertEquals($dto->status, $updatedAnime->status);
        $this->assertEquals($dto->rating, $updatedAnime->rating);
        $this->assertEquals($dto->episodes, $updatedAnime->episodes);
        $this->assertEquals(collect($dto->urls)->pluck('url'), $updatedAnime->urls->pluck('url'));
    }
}
