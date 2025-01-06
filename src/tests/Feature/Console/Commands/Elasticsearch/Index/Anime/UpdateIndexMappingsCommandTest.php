<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use Tests\Concerns\CanCreateMocks;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;

class UpdateIndexMappingsCommandTest extends TestCase
{
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeElasticsearchClient();
    }

    public function testCommandCanUpdateAnimeIndexMappings(): void
    {
        $this->elasticHandler->append(new JsonResponse(['index' => IndexEnum::ANIME_INDEX->value]));

        $this->artisan('elasticsearch:update-anime-index-mappings')->assertOk();
    }
}
