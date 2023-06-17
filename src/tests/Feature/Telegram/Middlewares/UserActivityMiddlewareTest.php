<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\CommandEnum;
use App\Facades\Telegram\History\UserHistory;
use App\Telegram\Middlewares\UserActivityMiddleware;
use Closure;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class UserActivityMiddlewareTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([(new UserActivityMiddleware())(...)]);
    }

    /**
     * @return void
     */
    public function testBotWillOnlyTrackUpdatesWithMessage(): void
    {
        UserHistory::shouldReceive('addLastActiveTime')
                   ->with(self::FAKE_TELEGRAM_ID)
                   ->once();

        $update   = $this->createFakeChatMemberUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillNotTrackRandomMessages(): void
    {
        UserHistory::shouldReceive('addLastActiveTime')
                   ->with(self::FAKE_TELEGRAM_ID)
                   ->once();

        UserHistory::shouldReceive('userLastExecutedCommand')
                   ->with(self::FAKE_TELEGRAM_ID)
                   ->andReturnFalse();

        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertFalse(UserHistory::userLastExecutedCommand(self::FAKE_TELEGRAM_ID));
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillTrackOnlyAvailableCommands(): void
    {
        UserHistory::shouldReceive('addLastActiveTime')
                   ->with(self::FAKE_TELEGRAM_ID)
                   ->once();

        UserHistory::shouldReceive('addExecutedCommand')
                   ->with(self::FAKE_TELEGRAM_ID, CommandEnum::ANIME_LIST->value)
                   ->once();

        UserHistory::shouldReceive('userLastExecutedCommand')
                   ->with(self::FAKE_TELEGRAM_ID)
                   ->andReturn(CommandEnum::ANIME_LIST->value);

        $update   = $this->createFakeTextMessageUpdate(message: CommandEnum::ANIME_LIST->value);
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(
            CommandEnum::ANIME_LIST->value,
            UserHistory::userLastExecutedCommand(self::FAKE_TELEGRAM_ID)
        );
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
