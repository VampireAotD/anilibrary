<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Telegram\Middleware\BotAccessMiddleware;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\MockObject\Exception;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Testing\FakeNutgram;

trait CanCreateMocks
{
    protected FakeNutgram $bot;
    protected MockHandler $elasticHandler;

    protected function setUpFakeBot(): void
    {
        $this->bot = $this->app->make(Nutgram::class);

        // Disabling bot middlewares for testing other handlers
        // To test middleware attach it ot bot in test class
        $this->bot->withoutMiddleware([
            BotAccessMiddleware::class,
        ]);
    }

    /**
     * @throws Exception
     */
    protected function setUpFakeCloudinary(): void
    {
        $this->app->instance(CloudinaryEngine::class, $this->createMock(CloudinaryEngine::class));
    }

    /**
     * @throws AuthenticationException
     */
    public function setUpFakeElasticsearchClient(): void
    {
        $this->elasticHandler = new MockHandler();

        $handlerStack = HandlerStack::create($this->elasticHandler);

        $client = new Client(['handler' => $handlerStack]);

        $this->app->instance(
            ElasticsearchClient::class,
            ClientBuilder::create()->setHttpClient($client)->build()
        );
    }
}
