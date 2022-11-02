<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\CommandEnum;
use App\Telegram\History\UserHistory;
use App\Telegram\Middlewares\UserActivityMiddleware;
use Closure;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class UserActivityMiddlewareTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    private TeleBot                             $bot;
    private LegacyMockInterface | MockInterface $userHistoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([new UserActivityMiddleware()]);
        $this->userHistoryMock = $this->createUserHistoryMock();
    }

    /**
     * @return void
     */
    public function testBotWillOnlyTrackUpdatesWithMessage(): void
    {
        $this->userHistoryMock
            ->shouldReceive('addLastActiveTime')
            ->with($this->fakeTelegramId)
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
        $this->userHistoryMock
            ->shouldReceive('addLastActiveTime')
            ->with($this->fakeTelegramId)
            ->once();

        $this->userHistoryMock
            ->shouldReceive('userLastExecutedCommand')
            ->with($this->fakeTelegramId)
            ->andReturnFalse();

        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertFalse(UserHistory::userLastExecutedCommand($this->fakeTelegramId));
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillTrackOnlyAvailableCommands(): void
    {
        $this->userHistoryMock
            ->shouldReceive('addLastActiveTime')
            ->with($this->fakeTelegramId)
            ->once();

        $this->userHistoryMock
            ->shouldReceive('addExecutedCommand')
            ->with($this->fakeTelegramId, CommandEnum::ANIME_LIST->value)
            ->once();

        $this->userHistoryMock
            ->shouldReceive('userLastExecutedCommand')
            ->with($this->fakeTelegramId)
            ->andReturn(CommandEnum::ANIME_LIST->value);

        $update   = $this->createFakeTextMessageUpdate(message: CommandEnum::ANIME_LIST->value);
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(
            CommandEnum::ANIME_LIST->value,
            UserHistory::userLastExecutedCommand($this->fakeTelegramId)
        );
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
