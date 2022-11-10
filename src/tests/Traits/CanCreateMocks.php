<?php

declare(strict_types=1);

namespace Tests\Traits;

use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
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
     * @return MockObject
     */
    public function createCloudinaryMock(): MockObject
    {
        $mock = $this->createMock(CloudinaryEngine::class);
        $this->app->instance(CloudinaryEngine::class, $mock);

        return $mock;
    }
}
