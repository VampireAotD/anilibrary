<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Conversations;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Enums\Telegram\Actions\CommandEnum;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Jobs\Image\UploadJob;
use App\Models\Anime;
use App\Services\Scraper\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\CanCreateFakeUpdates;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

class AddAnimeConversationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateMocks;
    use CanCreateFakeUpdates;
    use CanCreateFakeAnime;

    private CallbackDataFactory $callbackDataFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->callbackDataFactory = $this->app->make(CallbackDataFactory::class);
    }

    /**
     * @return array<array<string>>
     */
    public static function supportedUrlsProvider(): array
    {
        return [
            ['https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129'],
            ['https://animevost.org/tip/tv/5-naruto-shippuuden12.html'],
        ];
    }

    public function testBotWillNotScrapeUnsupportedUrl(): void
    {
        $this->bot->willStartConversation()
                  ->hearText(CommandEnum::ADD_ANIME_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText('test')
                  ->reply()
                  ->assertReplyMessage(['text' => __('validation.telegram.url')]);
    }

    #[DataProvider('supportedUrlsProvider')]
    public function testBotWillReturnAnimeWithoutScrapingIfUrlIsAlreadyInDatabase(string $url): void
    {
        $anime = $this->createAnimeWithRelations();
        $anime->urls()->create(['url' => $url]);

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage([
                      'text'         => __('telegram.conversations.add_anime.scrape_has_ended'),
                      'reply_markup' => [
                          'inline_keyboard' => [
                              [
                                  [
                                      'text'          => __('telegram.conversations.add_anime.view_anime'),
                                      'callback_data' => $this->callbackDataFactory->resolve(
                                          new ViewAnimeCallbackDataDTO($anime->id)
                                      ),
                                  ],
                              ],
                          ],
                      ],
                  ]);
    }

    #[DataProvider('supportedUrlsProvider')]
    public function testBotWillRespondWithFailureMessageIfScrapedDataWereInvalid(string $url): void
    {
        Bus::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'year'     => $this->faker->year,
                'type'     => $this->faker->randomAnimeType(),
                'status'   => $this->faker->randomAnimeStatus(),
                'episodes' => $this->faker->randomAnimeEpisodes(),
                'rating'   => $this->faker->randomAnimeRating(),
            ]),
        ]);

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.scrape_failed')]);

        Bus::assertNotDispatched(UploadJob::class);
    }

    #[DataProvider('supportedUrlsProvider')]
    public function testBotCanScrapeSupportedUrls(string $url): void
    {
        Bus::fake();
        Http::fake([
            Client::SCRAPE_ENDPOINT => Http::response([
                'title'    => $title = $this->faker->sentence,
                'image'    => $image = $this->faker->randomAnimeImage(),
                'type'     => $type = $this->faker->randomAnimeType(),
                'year'     => $year = $this->faker->year,
                'status'   => $status = $this->faker->randomAnimeStatus(),
                'episodes' => $episodes = $this->faker->randomAnimeEpisodes(),
                'rating'   => $rating = $this->faker->randomAnimeRating(),
            ]),
        ]);

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.add_anime.scrape_has_ended')]);

        $anime = Anime::query()->first();
        $this->assertInstanceOf(Anime::class, $anime);

        Bus::assertDispatched(UpsertAnimeJob::class);
        Bus::assertDispatched(UploadJob::class, fn(UploadJob $job): bool => $job->image === $image);

        $this->assertEquals($anime->title, $title);
        $this->assertEquals($anime->type, $type);
        $this->assertEquals($anime->year, $year);
        $this->assertEquals($anime->status, $status);
        $this->assertEquals($anime->episodes, $episodes);
        $this->assertEquals($anime->rating, $rating);
    }
}
