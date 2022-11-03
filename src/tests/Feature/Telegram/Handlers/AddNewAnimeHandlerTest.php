<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\Telegram\AddNewAnimeJob;
use App\Telegram\Handlers\AddNewAnimeHandler;
use Closure;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class AddNewAnimeHandlerTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates,
        WithFaker;

    private const SUPPORTED_URLS = [
        'https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129',
        'https://animevost.org/tip/tv/5-naruto-shippuuden12.html',
    ];

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bot = $this->createFakeBot()->fake();
        $this->bot->addHandler([AddNewAnimeHandler::class]);

        $this->createUserHistoryMock()
             ->shouldReceive('userLastExecutedCommand')
             ->withArgs([$this->fakeTelegramId])
             ->andReturn(CommandEnum::ADD_NEW_TITLE->value);
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

        $this->assertEquals(AnimeHandlerEnum::INVALID_URL->value, $response->text);
    }

    /**
     * @return void
     */
    public function testBotCanScrapeSupportedUrls(): void
    {
        Bus::fake();

        foreach (self::SUPPORTED_URLS as $supportedUrl) {
            $update = $this->createFakeTextMessageUpdate(message: $supportedUrl);
            // After each setUp in loop bot settings reverts to default for no reason,
            // and it considers itself not fake, but regular bot again,
            // so need to specify each iteration that its fake
            // TODO try to fix this
            $response = $this->bot->fake()->handleUpdate($update);

            Bus::assertDispatched(AddNewAnimeJob::class);
            $this->assertEquals(AnimeHandlerEnum::STARTED_PARSE_MESSAGE->value, $response->text);
        }
    }
}
