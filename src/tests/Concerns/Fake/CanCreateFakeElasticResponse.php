<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Anime;
use App\Models\Genre;
use App\Models\VoiceActing;
use Tests\Helpers\Elasticsearch\JsonResponse;

trait CanCreateFakeElasticResponse
{
    use CanCreateFakeAnime;

    public function createElasticResponseForAnimeWithRelations(int $quantity = 1): JsonResponse
    {
        $animeList = $this->createAnimeCollectionWithRelations($quantity);

        $elasticCollection = $animeList->map(fn(Anime $anime) => ['_source' => ['id' => $anime->id]]);

        return new JsonResponse([
            'hits' => [
                'total' => [
                    'value' => $animeList->count(),
                ],
                'hits' => $elasticCollection->toArray(),
            ],
        ]);
    }

    public function createElasticResponseForAnimeFacets(): JsonResponse
    {
        $animeList = $this->createAnimeCollectionWithRelations(quantity: 5);

        $types = $animeList->pluck('type')->map(
            fn(TypeEnum $type) => ['key' => $type->value, 'doc_count' => 5]
        )->toArray();

        $statuses = $animeList->pluck('status')->map(
            fn(StatusEnum $status) => ['key' => $status->value, 'doc_count' => 5]
        )->toArray();

        $voiceActing = VoiceActing::query()->withCount('anime')->get()->map(
            fn(VoiceActing $voiceActing) => ['key' => $voiceActing->name, 'doc_count' => $voiceActing->anime_count]
        )->toArray();

        $genres = Genre::query()->withCount('anime')->get()->map(
            fn(Genre $genre) => ['key' => $genre->name, 'doc_count' => $genre->anime_count]
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
