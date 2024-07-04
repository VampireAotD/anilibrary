<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Anime;

use App\Console\Commands\Anime\UpdateUnreleasedAnimeCommand;
use App\Enums\Anime\StatusEnum;
use App\Mail\Anime\FailedUnreleasedAnimeMail;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class UpdateUnreleasedAnimeCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->setUpFakeCloudinary();
    }

    public function testCommandWillSendMailWithAnimeInfoAndReasonIfItCouldNotUpdateInfoAndOwnerIsFound(): void
    {
        $anime = $this->createAnimeWithRelations(['status' => StatusEnum::ANNOUNCE->value]);
        $owner = $this->createOwner();

        Http::fake([
            '*' => Http::response(status: Response::HTTP_UNPROCESSABLE_ENTITY),
        ]);
        Mail::fake();

        $this->artisan(UpdateUnreleasedAnimeCommand::class)
             ->expectsOutput('Failed to update some anime, mail is queued')
             ->expectsOutput('All anime has been updated!')
             ->assertOk();

        Mail::assertQueued(
            FailedUnreleasedAnimeMail::class,
            function (FailedUnreleasedAnimeMail $mail) use ($anime, $owner) {
                $this->assertArrayHasKey($anime->id, $mail->failedList);

                return $mail->hasTo($owner->email);
            }
        );
    }

    public function testCommandCanUpdateAnimeInfo(): void
    {
        $this->markTestSkipped('Decide if this command is needed');

        $anime = $this->createAnimeWithRelations(['status' => StatusEnum::ANNOUNCE->value]);
        $this->createOwner();

        Http::fake([
            '*' => Http::response([
                'title'    => $anime->title,
                'type'     => $anime->type,
                'year'     => $anime->year,
                'status'   => StatusEnum::READY->value,
                'episodes' => $anime->episodes,
                'rating'   => $rating = $this->faker->randomAnimeRating(),
            ]),
        ]);

        Cloudinary::shouldReceive('destroy')->andReturnNull();
        Cloudinary::shouldReceive('uploadFile')->andReturnSelf();
        Cloudinary::shouldReceive('getSecurePath')->andReturn($anime->image->path);

        $this->artisan(UpdateUnreleasedAnimeCommand::class)
             ->expectsOutput('All anime has been updated!')
             ->assertOk();

        $anime->refresh();

        $this->assertEquals(StatusEnum::READY->value, $anime->status);
        $this->assertEquals($rating, $anime->rating);
    }
}
