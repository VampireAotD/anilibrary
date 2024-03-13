<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch\Index;

use App\Enums\Elasticsearch\IndexEnum;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Support\Facades\Log;

final readonly class AnimeIndexService
{
    public function __construct(public Client $client)
    {
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function multiMatchSearch(string $term): array
    {
        try {
            return $this->client->search([
                'index' => IndexEnum::ANIME_INDEX->value,
                'body'  => [
                    'query' => [
                        'multi_match' => [
                            'query'                => $term,
                            'fields'               => [
                                'title^8',
                                'status',
                                'rating',
                                'episodes',
                                'synonyms.synonym^5',
                                'genres.name^4',
                                'voice_acting.name',
                            ],
                            'type'                 => 'most_fields',
                            'analyzer'             => 'anime_analyzer',
                            'operator'             => 'AND',
                            'minimum_should_match' => '75%',
                            'fuzziness'            => 'AUTO',
                            'tie_breaker'          => 0.3,
                        ],
                    ],
                ],
            ])->asArray();
        } catch (ClientResponseException | ServerResponseException $e) {
            Log::error('Elasticsearch anime index multi match', [
                'exception_trace'   => $e->getTraceAsString(),
                'exception_message' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
