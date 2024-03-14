<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middleware;

use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Middleware\UserActivityMiddleware;
use SergiX44\Nutgram\Nutgram;
use Tests\TestCase;
use Tests\Concerns\CanCreateFakeUpdates;
use Tests\Concerns\CanCreateMocks;

class UserActivityMiddlewareTest extends TestCase
{
    use CanCreateMocks;
    use CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testBotWillOnlyTrackUpdatesWithSupportedMessages(): void
    {
        $bot = $this->bot;
        $bot->middleware(UserActivityMiddleware::class);

        // Added handler for tests
        $bot->onText('test', function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        UserStateFacade::shouldReceive('setLastActiveAt')->once();
        UserStateFacade::shouldReceive('addExecutedCommand')->never();

        $bot->hearText('test')->reply()->assertReplyMessage(['text' => 'test']);
    }

    public function testBotWillNotTrackRandomMessages(): void
    {
        UserStateFacade::shouldReceive('setLastActiveAt')->with(self::FAKE_TELEGRAM_ID)->once();
        UserStateFacade::shouldReceive('getLastExecutedCommand')->with(self::FAKE_TELEGRAM_ID)->andReturn('');

        $bot = $this->bot;
        $bot->middleware(UserActivityMiddleware::class);

        // Added handler for tests
        $bot->onText('test', function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        $bot->hearMessage($this->createFakeTextMessageUpdateData('test'))
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        $this->assertEmpty(UserStateFacade::getLastExecutedCommand(self::FAKE_TELEGRAM_ID));
    }

    public function testBotWillTrackOnlyAvailableCommands(): void
    {
        UserStateFacade::shouldReceive('setLastActiveAt')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->once();

        UserStateFacade::shouldReceive('addExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID, CommandButtonEnum::ANIME_LIST_BUTTON->value)
                       ->once();

        UserStateFacade::shouldReceive('getLastExecutedCommand')
                       ->with(self::FAKE_TELEGRAM_ID)
                       ->andReturn(CommandButtonEnum::ANIME_LIST_BUTTON->value);

        $bot = $this->bot;
        $bot->middleware(UserActivityMiddleware::class);

        // Rewrite original command for test
        $bot->onText(CommandButtonEnum::ANIME_LIST_BUTTON->value, function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        $bot->hearMessage($this->createFakeTextMessageUpdateData(CommandButtonEnum::ANIME_LIST_BUTTON->value))
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        $this->assertEquals(
            CommandButtonEnum::ANIME_LIST_BUTTON->value,
            UserStateFacade::getLastExecutedCommand(self::FAKE_TELEGRAM_ID)
        );
    }
}
