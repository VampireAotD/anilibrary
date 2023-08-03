<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Lists\Anime;

use App\Console\Commands\Lists\Anime\GenerateCommand;
use App\Mail\AnimeListMail;
use App\Repositories\Anime\AnimeRepositoryInterface;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeAnime;
use Tests\Traits\Fake\CanCreateFakeUsers;

class GenerateCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    private AnimeRepositoryInterface $animeRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->animeRepository = $this->app->make(AnimeRepositoryInterface::class);
    }

    public function testCommandWillNotGenerateAnimeListIfOwnerNotExist(): void
    {
        $this->artisan(GenerateCommand::class)->expectsOutput('Owner not found')->assertFailed();
    }

    public function testCommandCanGenerateAnimeList(): void
    {
        $this->createAnimeCollectionWithRelations(10);
        $owner = $this->createOwner();

        Mail::fake();
        Storage::fake('lists');

        $this->artisan(GenerateCommand::class)
             ->assertSuccessful()
             ->expectsOutput('Anime list successfully generated');

        $listFile = config('lists.anime.file');

        Storage::disk('lists')->assertExists($listFile);
        Mail::assertQueued(AnimeListMail::class, function (AnimeListMail $mail) use ($listFile, $owner) {
            $mail->build();

            return $mail->hasFrom(config('mail.from.address')) &&
                   $mail->hasTo($owner->email) &&
                   $mail->hasAttachmentFromStorageDisk('lists', $listFile);
        });

        $json = Storage::disk('lists')->get($listFile);

        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonString(
            $this->animeRepository->getAll(['id', 'title', 'status', 'rating', 'episodes',], [
                'urls:anime_id,url',
                'synonyms:anime_id,synonym',
                'image:id,model_id,path,alias',
                'genres:id,name',
                'voiceActing:id,name',
            ])->toJson(JSON_PRETTY_PRINT),
            $json
        );

        Storage::disk('lists')->delete($listFile);
        Storage::disk('lists')->assertMissing($listFile);
    }
}
