<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Facades\Telegram\History\UserHistory;
use App\Telegram\Handlers\CallbackQueryHandler;
use Closure;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\TeleBot;

class CallbackQueryHandlerTest extends TestCase
{
    use CanCreateMocks,
        CanCreateFakeUpdates,
        CanCreateFakeData;

    private TeleBot $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = $this->createFakeBot();
        $this->bot->addHandler([CallbackQueryHandler::class]);
        UserHistory::shouldReceive('addLastActiveTime')->once();
    }

    /**
     * @return void
     */
    public function testHandlerWillNotRespondToInvalidCallbackQuery(): void
    {
        $update   = $this->createFakeCallbackQueryUpdate(query: Str::random());
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Closure::class, $response);
        $this->assertNull($response());
    }
}
