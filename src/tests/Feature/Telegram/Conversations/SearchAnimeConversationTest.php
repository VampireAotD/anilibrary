<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Conversations;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;
use Tests\Traits\Fake\CanCreateFakeAnime;

class SearchAnimeConversationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->setUpFakeElasticsearchClient();
    }

    public function testBotWillRespondThatNoSearchResultsWereFound(): void
    {
        $this->elasticClient->addResponse(new JsonResponse(json_encode(['hits' => ['hits' => []]])));

        $this->bot->willStartConversation()
                  ->hearText(CommandEnum::SEARCH_ANIME_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.search_anime.example')])
                  ->hearText($this->faker->text)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.search_anime.no_results')]);
    }

    public function testBotWillRespondWithSearchResultsAndPagination(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations();

        $response = [
            'hits' => [
                'hits' => $animeList->map(fn(Anime $anime) => ['_source' => $anime->toArray()])->all(),
            ],
        ];

        $this->elasticClient->addResponse(new JsonResponse(json_encode($response)));

        // No previous search results as the conversation just started
        UserStateFacade::shouldReceive('getSearchResultPreview')->once()->andReturnNull();

        // Save given search results
        UserStateFacade::shouldReceive('saveSearchResult')->once();

        // Reset all executed commands
        UserStateFacade::shouldReceive('resetExecutedCommandsList')->once();

        // Retrieve search results from cache
        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn($animeList->pluck('id')->toArray());

        // Save previous search results
        UserStateFacade::shouldReceive('saveSearchResultPreview')->once();

        $anime = $animeList->first();
        $this->assertInstanceOf(Anime::class, $anime);

        $this->bot->willStartConversation()
                  ->hearText(CommandEnum::SEARCH_ANIME_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.search_anime.example')])
                  ->hearText($this->faker->text)
                  ->reply()
                  ->assertReplyMessage([
                      'photo'   => $anime->image->path,
                      'caption' => $anime->toTelegramCaption,
                  ]);
    }
}
