<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Lists\Anime;

use App\Mail\AnimeListMail;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;

class GenerateCommandTest extends TestCase
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

        $listFile = config('lists.anime.file');

        Storage::disk('lists')->assertExists($listFile);
        Mail::assertQueued(
            AnimeListMail::class,
            function (AnimeListMail $mail) use ($listFile) {
                $mail->build();

                return $mail->hasFrom(config('admin.email')) &&
                    $mail->hasTo(config('admin.email')) &&
                    $mail->hasAttachmentFromStorageDisk('lists', $listFile);
            }
        );

        $json = Storage::disk('lists')->get($listFile);

        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonString(
            $this->animeRepository->getAll(
                ['id', 'title', 'status', 'rating', 'episodes',],
                [
                    'urls:anime_id,url',
                    'synonyms:anime_id,synonym',
                    'image:id,model_id,path,alias',
                    'tags:id,name',
                    'genres:id,name',
                    'voiceActing:id,name',
                ]
            )->toJson(JSON_PRETTY_PRINT),
            $json
        );

        Storage::disk('lists')->delete($listFile);
        Storage::disk('lists')->assertMissing($listFile);
    }
}
