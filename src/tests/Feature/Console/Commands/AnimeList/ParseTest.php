<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\AnimeList;

use App\Enums\Telegram\AnimeStatusEnum;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;

class ParseTest extends TestCase
{
    use RefreshDatabase,
        CanCreateFakeData;

    private AnimeRepositoryInterface $animeRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->animeRepository = $this->app->make(AnimeRepositoryInterface::class);
    }

    /**
     * @return void
     */
    public function testCommandCannotParseAnimeListWithoutFile(): void
    {
        Storage::shouldReceive('disk->exists')->with(config('lists.anime.file'))->andReturnFalse();

        $this->artisan('anime-list:parse')
             ->assertFailed()
             ->expectsOutput('Anime list not found');
    }

    /**
     * @return void
     */
    public function testCommandCanParseAnimeList(): void
    {
        $this->createRandomAnimeWithRelations(10);

        Storage::shouldReceive('disk->exists')->with(config('lists.anime.file'))->andReturnTrue();
        Storage::shouldReceive('disk->get')
               ->with(config('lists.anime.file'))
               ->andReturn(
                   $this->animeRepository->getAll(
                       ['id', 'title', 'url', 'status', 'rating', 'episodes',],
                       ['image:id,model_id,path,alias', 'tags:id,name', 'genres:id,name', 'voiceActing:id,name',]
                   )->toJson(JSON_PRETTY_PRINT)
               );

        $this->refreshTestDatabase();

        $this->artisan('anime-list:parse')
             ->assertSuccessful()
             ->expectsOutput('Parsed anime list');

        $anime = $this->animeRepository->getAll()->first();

        $this->assertModelExists($anime);
        $this->assertNotEmpty($anime->title);
        $this->assertIsFloat($anime->rating);
        $this->assertContainsEquals($anime->status, AnimeStatusEnum::values());
        $this->assertNotNull($anime->image);
        $this->assertNotNull($anime->voiceActing);
        $this->assertNotNull($anime->genres);
    }
}
