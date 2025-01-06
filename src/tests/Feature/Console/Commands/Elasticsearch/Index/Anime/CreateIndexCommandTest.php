<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use Illuminate\Http\Response;
use Tests\Concerns\CanCreateMocks;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;

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
        $this->elasticHandler->append(new JsonResponse(['index' => IndexEnum::ANIME_INDEX->value]));

        $this->artisan('elasticsearch:create-anime-index')->assertFailed();
    }

    public function testCommandCanCreateAnimeIndex(): void
    {
        $this->elasticHandler->append(
            new JsonResponse(['index' => IndexEnum::ANIME_INDEX->value], Response::HTTP_NOT_FOUND)
        );

        $this->elasticHandler->append(new JsonResponse(['index' => IndexEnum::ANIME_INDEX->value]));

        $this->artisan('elasticsearch:create-anime-index')->assertOk();
    }
}
