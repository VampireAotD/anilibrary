<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middlewares;

use App\Enums\Telegram\Middlewares\ChatMemberStatusEnum;
use App\Facades\Telegram\History\UserHistory;
use App\Telegram\Middlewares\UserStatusMiddleware;
use Closure;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;

class UserStatusMiddlewareTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->bot->addHandler([(new UserStatusMiddleware())(...)]);
    }

    /**
     * @return void
     */
    public function testMiddlewareWillTrackOnlyChatMemberUpdates(): void
    {
        $update   = $this->createFakeStickerMessageUpdate();
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }

    /**
     * @return void
     */
    public function testMiddlewareWillDeleteUserActivityIfHeDeletedOrLeftChatWithBot(): void
    {
        UserHistory::shouldReceive('clearUserExecutedCommandsHistory')->once();
        $update   = $this->createFakeChatMemberUpdate(
            newChatMember: [
                'status'     => ChatMemberStatusEnum::KICKED->value,
                'until_date' => 0,
            ]
        );
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());

        UserHistory::shouldReceive('clearUserExecutedCommandsHistory')->once();
        $update   = $this->createFakeChatMemberUpdate(newChatMember: ['status' => ChatMemberStatusEnum::LEFT->value]);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
