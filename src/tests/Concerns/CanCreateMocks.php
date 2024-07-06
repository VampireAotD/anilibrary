<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Telegram\Middleware\BotAccessMiddleware;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Http\Mock\Client;
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
        ]);
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
