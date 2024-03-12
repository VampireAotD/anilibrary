<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Conversations;

use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Enums\Telegram\Actions\CommandEnum;
use App\Enums\Validation\Telegram\SupportedUrlRuleEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Models\Anime;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use Tests\Traits\Fake\CanCreateFakeAnime;

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
        $this->setUpFakeCloudinary();
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
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText('test')
                  ->reply()
                  ->assertReplyMessage(['text' => SupportedUrlRuleEnum::UNSUPPORTED_URL->value]);
    }

    #[DataProvider('supportedUrlsProvider')]
    public function testBotWillReturnAnimeWithoutScrapingIfUrlIsAlreadyInDatabase(string $url): void
    {
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->with(self::FAKE_TELEGRAM_ID)->once();

        $anime = $this->createAnimeWithRelations();
        $anime->urls()->create(['url' => $url]);

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage([
                      'text'         => __('telegram.commands.add_anime.scrape_has_ended'),
                      'reply_markup' => [
                          'inline_keyboard' => [
                              [
                                  [
                                      'text'          => __('telegram.commands.add_anime.view_anime'),
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
        Http::fake([
            '*' => [
                'status'   => $this->faker->randomAnimeStatus(),
                'episodes' => $this->faker->randomAnimeEpisodes(),
                'rating'   => $this->faker->randomAnimeRating(),
            ],
        ]);

        Cloudinary::shouldReceive('uploadFile')->andReturnSelf();
        Cloudinary::shouldReceive('getSecurePath')->andReturn($this->faker->imageUrl);
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->with(self::FAKE_TELEGRAM_ID)->once();

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.scrape_failed')]);
    }

    #[DataProvider('supportedUrlsProvider')]
    public function testBotCanScrapeSupportedUrls(string $url): void
    {
        Http::fake([
            '*' => [
                'title'    => $title = $this->faker->sentence,
                'status'   => $status = $this->faker->randomAnimeStatus(),
                'episodes' => $episodes = $this->faker->randomAnimeEpisodes(),
                'rating'   => $rating = $this->faker->randomAnimeRating(),
            ],
        ]);

        Bus::fake();

        Cloudinary::shouldReceive('uploadFile')->andReturnSelf();
        Cloudinary::shouldReceive('getSecurePath')->andReturn($this->faker->imageUrl);
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->with(self::FAKE_TELEGRAM_ID)->once();

        $this->bot->willStartConversation()
                  ->hearMessage($this->createFakeTextMessageUpdateData(CommandEnum::ADD_ANIME_COMMAND->value))
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.provide_url')])
                  ->assertActiveConversation()
                  ->hearText($url)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.commands.add_anime.scrape_has_ended')]);

        Bus::assertDispatched(UpsertAnimeJob::class);

        $anime = Anime::query()->first();
        $this->assertEquals($anime->title, $title);
        $this->assertEquals($anime->status, $status);
        $this->assertEquals($anime->episodes, $episodes);
        $this->assertEquals($anime->rating, $rating);
    }
}
