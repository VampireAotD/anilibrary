<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\Commands\CommandEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Middlewares\UserActivityMiddleware;
use Closure;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;

class UserActivityMiddlewareTest extends TestCase
{
    use CanCreateMocks;
    use CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([(new UserActivityMiddleware())(...)]);
    }

    /**
     * @return void
     */
    public function testBotWillOnlyTrackUpdatesWithMessage(): void
    {
        UserStateFacade::shouldReceive('setLastActiveAt')
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
        UserStateFacade::shouldReceive('setLastActiveAt')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->once();

        UserStateFacade::shouldReceive('getLastExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->andReturn('');

        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertEmpty(UserStateFacade::getLastExecutedCommand(self::FAKE_TELEGRAM_ID));
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testBotWillTrackOnlyAvailableCommands(): void
    {
        UserStateFacade::shouldReceive('setLastActiveAt')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->once();

        UserStateFacade::shouldReceive('addExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID, CommandEnum::ANIME_LIST_BUTTON->value)
                       ->once();

        UserStateFacade::shouldReceive('getLastExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->andReturn(CommandEnum::ANIME_LIST_BUTTON->value);

        $update   = $this->createFakeTextMessageUpdate(CommandEnum::ANIME_LIST_BUTTON->value);
        $response = $this->bot->handleUpdate($update);

        $this->assertEquals(
            CommandEnum::ANIME_LIST_BUTTON->value,
            UserStateFacade::getLastExecutedCommand(self::FAKE_TELEGRAM_ID)
        );
        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
