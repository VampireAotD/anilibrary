<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\Telegram\PickRandomAnimeJob;
use App\Jobs\Telegram\ProvideAnimeListJob;
use App\Telegram\Handlers\CommandHandler;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\TeleBot;

class CommandHandlerTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([CommandHandler::class]);
    }

    /**
     * @return void
     */
    public function testBotCanCreateAnime(): void
    {
        $cases = [CommandEnum::ADD_NEW_TITLE_COMMAND->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value];

        foreach ($cases as $case) {
            $update   = $this->createFakeTextMessageUpdate(message: $case);
            $response = $this->bot->fake()->handleUpdate($update);

            $this->assertInstanceOf(Message::class, $response);
            $this->assertEquals(AnimeHandlerEnum::PROVIDE_URL->value, $response->text);
        }
    }

    /**
     * @return void
     */
    public function testBotCanPickRandomAnime(): void
    {
        $cases = [CommandEnum::RANDOM_ANIME_COMMAND->value, CommandEnum::RANDOM_ANIME->value];

        Bus::fake();
        foreach ($cases as $case) {
            $update = $this->createFakeTextMessageUpdate(message: $case);
            $this->bot->handleUpdate($update);

            Bus::assertDispatched(PickRandomAnimeJob::class);
        }
    }

    /**
     * @return void
     */
    public function testBotCanProvideAnimeList(): void
    {
        $cases = [CommandEnum::ANIME_LIST_COMMAND->value, CommandEnum::ANIME_LIST->value];

        Bus::fake();
        foreach ($cases as $case) {
            $update = $this->createFakeTextMessageUpdate(message: $case);
            $this->bot->handleUpdate($update);

            Bus::assertDispatched(ProvideAnimeListJob::class);
        }
    }
}
