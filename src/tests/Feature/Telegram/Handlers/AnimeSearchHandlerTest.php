<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Handlers;

use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\AnimeSearchHandlerEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Telegram\Handlers\AnimeSearchHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\Elasticsearch\Response;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateFakeUpdates;
use Tests\Traits\CanCreateMocks;
use WeStacks\TeleBot\Objects\Message;

class AnimeSearchHandlerTest extends TestCase
{
    use RefreshDatabase,
        CanCreateMocks,
        CanCreateFakeUpdates,
        CanCreateFakeData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->setUpFakeElasticsearchClient();

        $this->bot->addHandler([AnimeSearchHandler::class]);

        UserStateFacade::shouldReceive('getLastExecutedCommand')->with(self::FAKE_TELEGRAM_ID)->andReturn(
            CommandEnum::ANIME_SEARCH_COMMAND->value
        );
    }

    public function testBotWillRespondThatNoSearchResultsWereFound(): void
    {
        $this->mockClient->addResponse(
            new Response(json_encode(['hits' => ['hits' => []]]), headers: ['Content-type' => 'application/json'])
        );

        $update   = $this->createFakeTextMessageUpdate($this->faker->sentence);
        $response = $this->bot->handleUpdate($update);

        $this->assertInstanceOf(Message::class, $response);
        $this->assertEquals(AnimeSearchHandlerEnum::NO_SEARCH_RESULTS->value, $response->text);
    }
}
