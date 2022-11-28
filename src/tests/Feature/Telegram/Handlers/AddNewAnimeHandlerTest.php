<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Enums\Validation\SupportedUrlEnum;
use App\Facades\Telegram\History\UserHistory;
use App\Jobs\Telegram\AddNewAnimeJob;
use App\Telegram\Handlers\AddNewAnimeHandler;
use Closure;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\TeleBot;

class AddNewAnimeHandlerTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates,
        WithFaker;

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([AddNewAnimeHandler::class]);

        UserHistory::shouldReceive('userLastExecutedCommand')
                   ->withArgs([$this->fakeTelegramId])
                   ->andReturn(CommandEnum::ADD_NEW_TITLE->value);
    }

    /**
     * @return array<array<string>>
     */
    public function supportedUrlsProvider(): array
    {
        return [
            ['https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129'],
            ['https://animevost.org/tip/tv/5-naruto-shippuuden12.html'],
        ];
    }

    /**
     * @return void
     */
    public function testBotWillNotScrapeInvalidMessage(): void
    {
        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillNotScrapeUnsupportedUrl(): void
    {
        $update   = $this->createFakeTextMessageUpdate(message: $this->faker->url);
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(SupportedUrlEnum::UNSUPPORTED_URL->value, $response->text);
    }

    /**
     * @dataProvider supportedUrlsProvider
     * @param string $url
     * @return void
     */
    public function testBotCanScrapeSupportedUrls(string $url): void
    {
        Bus::fake();

        $update = $this->createFakeTextMessageUpdate(message: $url);

        /** @see https://github.com/westacks/telebot/issues/58 */
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(AnimeHandlerEnum::PARSE_STARTED->value, $response->text);
        Bus::assertDispatched(AddNewAnimeJob::class);
    }
}
