<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use Illuminate\Http\Response as ResponseStatus;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;
use Tests\Traits\CanCreateMocks;

class CreateIndexCommandTest extends TestCase
{
    use CanCreateMocks;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeElasticsearchClient();
    }

    public function testCommandWillNotCreateAnimeIndexIfItIsAlreadyExists(): void
    {
        $this->elasticClient->addResponse(new JsonResponse(json_encode(['index' => IndexEnum::ANIME_INDEX->value])));

        $this->artisan('elasticsearch:create-anime-index')->assertFailed();
    }

    public function testCommandCanCreateAnimeIndex(): void
    {
        $this->elasticClient->addResponse(
            new JsonResponse(json_encode(['index' => IndexEnum::ANIME_INDEX->value]), ResponseStatus::HTTP_NOT_FOUND)
        );

        $this->elasticClient->addResponse(new JsonResponse(json_encode(['index' => IndexEnum::ANIME_INDEX->value])));

        $this->artisan('elasticsearch:create-anime-index')->assertOk();
    }
}
