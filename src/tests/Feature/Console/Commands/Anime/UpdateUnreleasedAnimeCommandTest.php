<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Anime;

use App\Console\Commands\Anime\UpdateUnreleasedAnimeCommand;
use App\Enums\Anime\StatusEnum;
use App\Mail\Anime\NotUpdatedAnimeMail;
use App\Services\Scraper\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class UpdateUnreleasedAnimeCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;
    use CanCreateFakeAnime;

    public function testCommandWillSendMailWithAnimeInfoAndReasonIfItCouldNotUpdateInfoAndOwnerIsFound(): void
    {
        $anime = $this->createAnimeWithRelations(['status' => StatusEnum::ANNOUNCE]);
        $owner = $this->createOwner();

        Mail::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response(status: Response::HTTP_UNPROCESSABLE_ENTITY),
        ]);

        $this->artisan(UpdateUnreleasedAnimeCommand::class)
             ->expectsOutput('Failed to update some anime, mail is queued')
             ->assertFailed();

        Mail::assertQueued(
            NotUpdatedAnimeMail::class,
            function (NotUpdatedAnimeMail $mail) use ($anime, $owner) {
                $this->assertArrayHasKey($anime->id, $mail->failedList);

                return $mail->hasTo($owner->email);
            }
        );
    }

    public function testCommandCanUpdateAnimeInfo(): void
    {
        $this->createOwner();
        $anime = $this->createAnimeWithRelations(['status' => StatusEnum::ANNOUNCE]);

        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'    => $anime->title,
                'type'     => $anime->type,
                'year'     => $anime->year,
                'status'   => StatusEnum::RELEASED,
                'episodes' => $episodes = $this->faker->randomAnimeEpisodes(),
                'rating'   => $rating   = $this->faker->randomAnimeRating(),
            ]),
        ]);

        $this->artisan(UpdateUnreleasedAnimeCommand::class)
             ->expectsOutput('All anime has been updated!')
             ->assertOk();

        $anime->refresh();

        $this->assertEquals(StatusEnum::RELEASED, $anime->status);
        $this->assertEquals($rating, $anime->rating);
        $this->assertEquals($episodes, $anime->episodes);
    }
}
