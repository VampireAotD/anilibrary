<?php

declare(strict_types=1);

namespace Tests\Traits;

use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Http\Mock\Client;
use PHPUnit\Framework\MockObject\Exception;
use WeStacks\TeleBot\Laravel\TeleBot as LaravelWrapper;
use WeStacks\TeleBot\TeleBot;

/**
 * Trait CanCreateMocks
 * @package Tests\Traits
 */
trait CanCreateMocks
{
    protected TeleBot $bot;
    protected Client  $mockClient;

    protected function setUpFakeBot(): void
    {
        $bot = LaravelWrapper::bot()->fake();
        $bot->clearHandlers();

        $this->bot = $bot;
    }

    /**
     * @throws Exception
     */
    public function setUpFakeCloudinary(): void
    {
        $mock = $this->createMock(CloudinaryEngine::class);

        $this->app->instance(CloudinaryEngine::class, $mock);
    }

    public function setUpFakeElasticsearchClient(): void
    {
        $mockClient = new Client();
        $this->app->instance(ElasticsearchClient::class, ClientBuilder::create()->setHttpClient($mockClient)->build());
        $this->mockClient = $mockClient;
    }
}
