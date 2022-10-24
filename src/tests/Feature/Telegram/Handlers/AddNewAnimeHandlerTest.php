<?php
declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\AddNewAnimeJob;
use App\Telegram\Handlers\AddNewAnimeHandler;
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

    private const ANIME_GO_URL = 'https://animego.org/anime/blich-tysyacheletnyaya-krovavaya-voyna-2129';

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler(AddNewAnimeHandler::class);
        $this->createUserHistoryMock()
             ->shouldReceive('userLastExecutedCommand')
             ->withArgs([$this->fakeTelegramId])
             ->andReturn(CommandEnum::ADD_NEW_TITLE->value);
    }

    public function testBotWillNotScrapeInvalid(): void
    {
        $update   = $this->createFakeMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(AnimeHandlerEnum::INVALID_URL->value, $response->text);
    }

    public function testBotWillNotScrapeUnsupportedUrl(): void
    {
        $update   = $this->createFakeMessageUpdate(message: $this->faker->url);
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(AnimeHandlerEnum::INVALID_URL->value, $response->text);
    }

    public function testBotCanScrapeSupportedUrls(): void
    {
        Bus::fake();

        $update   = $this->createFakeMessageUpdate(message: self::ANIME_GO_URL);
        $response = $this->bot->handleUpdate($update);

        Bus::assertDispatched(AddNewAnimeJob::class);
        $this->assertEquals(AnimeHandlerEnum::STARTED_PARSE_MESSAGE->value, $response->text);
    }
}
