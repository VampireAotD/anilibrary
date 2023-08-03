<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\RandomAnimeEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Handlers\RandomAnimeHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;

class RandomAnimeHandlerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUpdates;
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([RandomAnimeHandler::class]);

        UserStateFacade::shouldReceive('getLastExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->andReturn(CommandEnum::RANDOM_ANIME_COMMAND->value);

        UserStateFacade::shouldReceive('resetExecutedCommandsList')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->once();
    }

    public function testHandlerWillRespondWithTextMessageIfDatabaseIsEmpty(): void
    {
        $update   = $this->createFakeTextMessageUpdate(CommandEnum::RANDOM_ANIME_COMMAND->value);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(RandomAnimeEnum::UNABLE_TO_FIND_ANIME->value, $response->text);
    }
}
