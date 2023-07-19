<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Lists\Anime;

use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use App\Repositories\Anime\AnimeRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;

class ParseCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;

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
        $this->createAnimeCollectionWithRelations(10);

        Storage::shouldReceive('disk->exists')->with(config('lists.anime.file'))->andReturnTrue();
        Storage::shouldReceive('disk->get')
               ->with(config('lists.anime.file'))
               ->andReturn(
                   $this->animeRepository->getAll(
                       ['id', 'title', 'status', 'rating', 'episodes'],
                       [
                           'urls:anime_id,url',
                           'synonyms:anime_id,synonym',
                           'image:id,model_id,path,alias',
                           'genres:id,name',
                           'voiceActing:id,name',
                       ]
                   )->toJson(JSON_PRETTY_PRINT)
               );

        $this->refreshTestDatabase();

        $this->artisan('anime-list:parse')
             ->assertSuccessful()
             ->expectsOutput('Parsed anime list');

        /** @var Anime $anime */
        $anime = $this->animeRepository->getAll()->first();

        $this->assertModelExists($anime);
        $this->assertNotEmpty($anime->title);
        $this->assertIsFloat($anime->rating);
        $this->assertContainsEquals($anime->status, AnimeStatusEnum::values());

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
