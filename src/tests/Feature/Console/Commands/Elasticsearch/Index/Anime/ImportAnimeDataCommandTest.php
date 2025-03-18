<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Console\Commands\Elasticsearch\Index\Anime\ImportAnimeDataCommand;
use App\Enums\Elasticsearch\IndexEnum;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\Concerns\Fake\CanCreateFakeElasticClient;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;

class ImportAnimeDataCommandTest extends TestCase
{
    use RefreshDatabase;
    use CanCreateFakeAnime;
    use CanCreateFakeElasticClient;

    #[\Override]
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

        $items = $animeList->map(
            static fn(Anime $anime) => ['index' => IndexEnum::ANIME_INDEX->value, 'id' => $anime->id]
        );

        $this->elasticHandler->append(new JsonResponse(['errors' => false, 'items' => $items->toArray()]));

        $this->artisan(ImportAnimeDataCommand::class)->assertOk();
    }
}
