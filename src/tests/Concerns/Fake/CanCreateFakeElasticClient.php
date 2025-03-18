<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Anime;
use App\Models\Genre;
use App\Models\VoiceActing;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Tests\Helpers\Elasticsearch\JsonResponse;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait CanCreateFakeElasticClient
{
    use CanCreateFakeAnime;

    protected MockHandler $elasticHandler;

    /**
     * @throws AuthenticationException
     */
    protected function setUpFakeElasticsearchClient(): void
    {
        $this->elasticHandler = new MockHandler();

        $handlerStack = HandlerStack::create($this->elasticHandler);

        $client = new Client(['handler' => $handlerStack]);

        $this->app->instance(
            ElasticsearchClient::class,
            ClientBuilder::create()->setHttpClient($client)->build()
        );
    }

    protected function createElasticResponseForAnimeWithRelations(int $quantity = 1): JsonResponse
    {
        $animeList = $this->createAnimeCollectionWithRelations($quantity);

        $elasticCollection = $animeList->map(static fn(Anime $anime) => ['_source' => ['id' => $anime->id]]);

        return new JsonResponse([
            'hits' => [
                'total' => [
                    'value' => $animeList->count(),
                ],
                'hits' => $elasticCollection->toArray(),
            ],
        ]);
    }

    protected function createElasticResponseForAnimeFacets(): JsonResponse
    {
        $animeList = $this->createAnimeCollectionWithRelations(quantity: 5);

        $types = $animeList->pluck('type')->map(
            static fn(TypeEnum $type) => ['key' => $type->value, 'doc_count' => 5]
        )->toArray();

        $statuses = $animeList->pluck('status')->map(
            static fn(StatusEnum $status) => ['key' => $status->value, 'doc_count' => 5]
        )->toArray();

        $voiceActing = VoiceActing::query()->withCount('anime')->get()->map(
            static fn(VoiceActing $voiceActing) => [
                'key'       => $voiceActing->name,
                'doc_count' => $voiceActing->anime_count,
            ]
        )->toArray();

        $genres = Genre::query()->withCount('anime')->get()->map(
            static fn(Genre $genre) => ['key' => $genre->name, 'doc_count' => $genre->anime_count]
        )->toArray();

        return new JsonResponse([
            'aggregations' => [
                'min_year' => [
                    'value' => $animeList->min('year'),
                ],
                'max_year' => [
                    'value' => $animeList->max('year'),
                ],
                'types' => [
                    'buckets' => $types,
                ],
                'statuses' => [
                    'buckets' => $statuses,
                ],
                'genres' => [
                    'buckets' => $genres,
                ],
                'voice_acting' => [
                    'buckets' => $voiceActing,
                ],
            ],
        ]);
    }
}
