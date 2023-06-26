<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use Illuminate\Http\Response as ResponseStatus;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;

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
        $this->mockClient->addResponse(
            new JsonResponse(json_encode(['index' => IndexEnum::ANIME_INDEX->value]), ResponseStatus::HTTP_OK)
        );

        $this->artisan('elasticsearch:update-anime-index-mappings')->assertOk();
    }
}
