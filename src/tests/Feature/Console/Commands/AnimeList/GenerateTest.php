<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\AnimeList;

use App\Mail\AnimeListMail;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;

class GenerateTest extends TestCase
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
    public function testCommandCanGenerateAnimeList(): void
    {
        $this->createRandomAnimeWithRelations(10);

        Mail::fake();
        Storage::fake('lists');

        $this->artisan('anime-list:generate')
             ->assertSuccessful()
             ->expectsOutput('Anime list successfully generated');

        Storage::disk('lists')->assertExists(config('lists.anime.file'));
        Mail::assertQueued(AnimeListMail::class);

        $json = Storage::disk('lists')->get(config('lists.anime.file'));

        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonString(
            $this->animeRepository->getAll(
                ['id', 'title', 'url', 'status', 'rating', 'episodes',],
                ['image:id,model_id,path,alias', 'tags:id,name', 'genres:id,name', 'voiceActing:id,name',]
            )->toJson(JSON_PRETTY_PRINT),
            $json
        );

        Storage::disk('lists')->delete(config('lists.anime.file'));
        Storage::disk('lists')->assertMissing(config('lists.anime.file'));
    }
}
