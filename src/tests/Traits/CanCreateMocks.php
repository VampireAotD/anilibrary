<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Telegram\Middleware\BotAccessMiddleware;
use App\Telegram\Middleware\UserActivityMiddleware;
use App\Telegram\Middleware\UserStatusMiddleware;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Http\Mock\Client;
use PHPUnit\Framework\MockObject\Exception;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Testing\FakeNutgram;

trait CanCreateMocks
{
    protected Client      $elasticClient;
    protected FakeNutgram $bot;

    protected function setUpFakeBot(): void
    {
        $this->bot = $this->app->make(Nutgram::class);

        // Disabling bot middlewares for testing other handlers
        // To test middleware attach it ot bot in test class
        $this->bot->withoutMiddleware([
            BotAccessMiddleware::class,
            UserStatusMiddleware::class,
            UserActivityMiddleware::class,
        ]);
    }

    /**
     * @throws Exception
     */
    public function setUpFakeCloudinary(): void
    {
        $mock = $this->createMock(CloudinaryEngine::class);

        $this->app->instance(CloudinaryEngine::class, $mock);
    }

    /**
     * @throws AuthenticationException
     */
    public function setUpFakeElasticsearchClient(): void
    {
        $mockClient = new Client();

        $this->app->instance(ElasticsearchClient::class, ClientBuilder::create()->setHttpClient($mockClient)->build());
        $this->elasticClient = $mockClient;
    }
}
