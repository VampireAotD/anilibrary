<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;
use Tests\Traits\Fake\CanCreateFakeAnime;

class SyncAnimeDataCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeElasticsearchClient();
    }

    /**
     * A basic feature test example.
     */
    public function testCommandWillSyncAnimeDataToElasticsearch(): void
    {
        $animeList = $this->createAnimeCollectionWithRelations(2);

        $items = $animeList->map(fn(Anime $anime) => ['index' => IndexEnum::ANIME_INDEX->value, 'id' => $anime->id]);

        $this->elasticClient->addResponse(
            new JsonResponse(json_encode(['errors' => false, 'items' => $items->toArray()]))
        );

        $this->artisan('elasticsearch:sync-anime')->assertOk();
    }
}
