<?php
declare(strict_types=1);

namespace Tests\Traits;

use App\Telegram\History\UserHistory;
use Mockery;
use WeStacks\TeleBot\Laravel\TeleBot as LaravelWrapper;
use WeStacks\TeleBot\TeleBot;

trait CanCreateMocks
{
    public function createFakeBot(): TeleBot
    {
        $bot = LaravelWrapper::bot()->fake();
        $bot->clearHandlers();

        return $bot;
    }

    public function createUserHistoryMock(): Mockery\MockInterface | Mockery\LegacyMockInterface
    {
        return Mockery::mock('overload:' . UserHistory::class);
    }
}
