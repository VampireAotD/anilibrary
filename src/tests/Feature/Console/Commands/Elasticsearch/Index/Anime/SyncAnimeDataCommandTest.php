<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;
use Tests\Traits\CanCreateFakeData;
use Tests\Traits\CanCreateMocks;

class SyncAnimeDataCommandTest extends TestCase
{
    use RefreshDatabase,
        CanCreateMocks,
        CanCreateFakeData;

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
        $animeList = $this->createRandomAnimeWithRelations();

        $items = $animeList->map(fn(Anime $anime) => ['index' => IndexEnum::ANIME_INDEX->value, 'id' => $anime->id]);

        $this->mockClient->addResponse(
            new JsonResponse(json_encode(['errors' => false, 'items' => $items->toArray()]))
        );

        $this->artisan('elasticsearch:sync-anime')->assertOk();
    }
}
