<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\CommandEnum;
use App\Enums\Telegram\Handlers\AddAnimeHandlerEnum;
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
     * @return array<array<string>>
     */
    public function addNewTitleCommandProvider(): array
    {
        return [
            [CommandEnum::ADD_NEW_TITLE_COMMAND->value],
            [CommandEnum::ADD_ANIME_BUTTON->value],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function randomAnimeCommandProvider(): array
    {
        return [
            [CommandEnum::RANDOM_ANIME_COMMAND->value],
            [CommandEnum::RANDOM_ANIME_BUTTON->value],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function animeListCommandProvider(): array
    {
        return [
            [CommandEnum::ANIME_LIST_COMMAND->value],
            [CommandEnum::ANIME_LIST_BUTTON->value],
        ];
    }

    /**
     * @dataProvider addNewTitleCommandProvider
     * @param string $command
     * @return void
     */
    public function testBotCanCreateAnime(string $command): void
    {
        $update   = $this->createFakeTextMessageUpdate(message: $command);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(AddAnimeHandlerEnum::PROVIDE_URL->value, $response->text);
    }

    /**
     * @dataProvider randomAnimeCommandProvider
     * @param string $command
     * @return void
     */
    public function testBotCanPickRandomAnime(string $command): void
    {
        Bus::fake();
        $update = $this->createFakeTextMessageUpdate(message: $command);
        $this->bot->handleUpdate($update);

        Bus::assertDispatched(PickRandomAnimeJob::class);
    }

    /**
     * @dataProvider animeListCommandProvider
     * @param string $command
     * @return void
     */
    public function testBotCanProvideAnimeList(string $command): void
    {
        Bus::fake();
        $update = $this->createFakeTextMessageUpdate(message: $command);
        $this->bot->handleUpdate($update);

        Bus::assertDispatched(ProvideAnimeListJob::class);
    }
}
