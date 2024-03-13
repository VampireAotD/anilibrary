<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middleware;

use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Middleware\UserStatusMiddleware;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ChatMemberStatus;
use SergiX44\Nutgram\Telegram\Properties\UpdateType;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;

class UserStatusMiddlewareTest extends TestCase
{
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testMiddlewareWillTrackOnlyChatMemberUpdates(): void
    {
        $bot = $this->bot;
        $bot->middleware(UserStatusMiddleware::class);

        // Added handler for tests
        $bot->onText('test', function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        $bot->hearText('test')->reply()->assertReplyMessage(['text' => 'test']);
    }

    public function testMiddlewareWillDeleteUserActivityIfHeDeletedOrLeftChatWithBot(): void
    {
        $bot = $this->bot;
        $bot->middleware(UserStatusMiddleware::class);

        // Added handler for tests
        $bot->onMyChatMember(function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::KICKED->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::LEFT->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::RESTRICTED->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);
    }

    public function testMiddlewareWontDeleteUserActivityIfItReceivedOtherChatMemberStatuses(): void
    {
        $bot = $this->bot;
        $bot->middleware(UserStatusMiddleware::class);

        // Added handler for tests
        $bot->onMyChatMember(function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::ADMINISTRATOR->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::CREATOR->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);

        $bot->hearUpdateType(UpdateType::MY_CHAT_MEMBER, [
            'text'            => 'test',
            'new_chat_member' => ['status' => ChatMemberStatus::MEMBER->value],
        ])
            ->reply()
            ->assertReplyMessage(['text' => 'test']);
    }
}
