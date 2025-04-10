<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Conversations;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Facades\Telegram\State\UserStateFacade;
use App\Models\Anime;
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeElasticClient;
use Tests\Concerns\Fake\CanCreateFakeTelegramBot;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;

final class SearchAnimeConversationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeTelegramBot;
    use CanCreateFakeElasticClient;
    use CanCreateFakeAnime;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
        $this->setUpFakeElasticsearchClient();
    }

    public function testBotWillRespondThatNoSearchResultsWereFound(): void
    {
        $this->elasticHandler->append(new JsonResponse(['hits' => ['hits' => []]]));

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

        $this->elasticHandler->append(new JsonResponse($response));

        // There must be no previous search results as the conversation just started
        UserStateFacade::shouldReceive('getSearchResultPreview')->once()->andReturnNull();

        // Save given search results
        UserStateFacade::shouldReceive('saveSearchResult')->once();

        // Retrieve search results from cache
        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn($animeList->pluck('id')->toArray());

        // Save previous search results
        UserStateFacade::shouldReceive('saveSearchResultPreview')->once();

        $anime = $animeList->first();

        $this->bot->willStartConversation()
                  ->hearText(CommandEnum::SEARCH_ANIME_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.search_anime.example')])
                  ->hearText($this->faker->text)
                  ->reply()
                  ->assertReplyMessage([
                      'photo'   => $anime->image->path,
                      'caption' => (string) AnimeCaptionText::fromAnime($anime),
                  ]);
    }

    public function testBotWillDeletePreviousSearchResultsOnNewSearch(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations();

        $response = [
            'hits' => [
                'hits' => $animeList->map(fn(Anime $anime) => ['_source' => $anime->toArray()])->all(),
            ],
        ];

        $this->elasticHandler->append(new JsonResponse($response));

        // Get previous search results
        UserStateFacade::shouldReceive('getSearchResultPreview')->once()->andReturn($this->faker->randomNumber());

        // Delete previous search results
        UserStateFacade::shouldReceive('removeSearchResultPreview')->once();

        // Save given search results
        UserStateFacade::shouldReceive('saveSearchResult')->once();

        // Retrieve search results from cache
        UserStateFacade::shouldReceive('getSearchResult')->once()->andReturn($animeList->pluck('id')->toArray());

        // Save previous search results
        UserStateFacade::shouldReceive('saveSearchResultPreview')->once();

        $anime = $animeList->first();

        $this->bot->willStartConversation()
                  ->hearText(CommandEnum::SEARCH_ANIME_COMMAND->value)
                  ->reply()
                  ->assertReplyMessage(['text' => __('telegram.conversations.search_anime.example')])
                  ->hearText($this->faker->text)
                  ->reply()
                  ->assertCalled('deleteMessage');
    }
}
