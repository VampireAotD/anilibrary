<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Telegram\History\UserHistory;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use WeStacks\TeleBot\Laravel\TeleBot as LaravelWrapper;
use WeStacks\TeleBot\TeleBot;

/**
 * Trait CanCreateMocks
 * @package Tests\Traits
 */
trait CanCreateMocks
{
    /**
     * @return TeleBot
     */
    public function createFakeBot(): TeleBot
    {
        $bot = LaravelWrapper::bot()->fake();
        $bot->clearHandlers();

        return $bot;
    }

    /**
     * @return Mockery\MockInterface|Mockery\LegacyMockInterface
     */
    public function createUserHistoryMock(): Mockery\MockInterface | Mockery\LegacyMockInterface
    {
        return Mockery::mock('overload:' . UserHistory::class);
    }

    /**
     * @return MockObject
     */
    public function createCloudinaryMock(): MockObject
    {
        $mock = $this->createMock(CloudinaryEngine::class);
        $this->app->instance(CloudinaryEngine::class, $mock);

        return $mock;
    }
}
