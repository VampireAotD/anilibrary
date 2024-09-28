<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch\Index;

use App\DTO\Service\Elasticsearch\Anime\AnimeFacetDTO;
use App\DTO\Service\Elasticsearch\Anime\AnimePaginationDTO;
use App\Enums\Elasticsearch\IndexEnum;
use App\Filters\ColumnFilter;
use App\Filters\RelationFilter;
use App\Filters\WhereInFilter;
use App\Repositories\Anime\AnimeRepositoryInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Transport\Exception\NoNodeAvailableException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

final readonly class AnimeIndexService
{
    public function __construct(
        private Client                   $client,
        private AnimeRepositoryInterface $animeRepository
    ) {
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
                                'title^3',
                                'synonyms.synonym',
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
        } catch (ClientResponseException | ServerResponseException | NoNodeAvailableException $exception) {
            Log::error('Elasticsearch anime index multi match', [
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    public function paginate(AnimePaginationDTO $dto): array
    {
        try {
            $response = $this->client->search([
                'index' => IndexEnum::ANIME_INDEX->value,
                'body'  => [
                    'from'  => ($dto->page - 1) * $dto->perPage,
                    'size'  => $dto->perPage,
                    'query' => [
                        'bool' => [
                            'filter' => $dto->getMappedFilters(),
                        ],
                    ],
                    'sort'  => $dto->sort,
                ],
            ])->asArray();

            $ids = Arr::pluck($response['hits']['hits'], '_source.id');

            return $this->animeRepository->withFilters([
                new ColumnFilter(['id', 'title', 'type', 'episodes', 'rating', 'status', 'year']),
                new RelationFilter(['image:id,path', 'synonyms', 'genres:id,name', 'voiceActing:id,name']),
                new WhereInFilter('id', $ids),
            ])->getAll()->toArray();
        } catch (ClientResponseException | ServerResponseException | NoNodeAvailableException $exception) {
            return [];
        }
    }

    public function getFacets(): array
    {
        try {
            $facets = $this->client->search([
                'index' => IndexEnum::ANIME_INDEX->value,
                'body'  => [
                    'size' => 0,
                    'aggs' => [
                        'min_year'     => [
                            'min' => [
                                'field' => 'year',
                            ],
                        ],
                        'max_year'     => [
                            'max' => [
                                'field' => 'year',
                            ],
                        ],
                        'types'        => [
                            'terms' => [
                                'field' => 'type',
                            ],
                        ],
                        'statuses'     => [
                            'terms' => [
                                'field' => 'status',
                            ],
                        ],
                        'genres'       => [
                            'terms' => [
                                'field' => 'genres.name',
                                'size'  => 50,
                            ],
                        ],
                        'voice_acting' => [
                            'terms' => [
                                'field' => 'voice_acting.name',
                            ],
                        ],
                    ],
                ],
            ])->asArray();

            return AnimeFacetDTO::fromArray($facets)->toArray();
        } catch (ClientResponseException | ServerResponseException | NoNodeAvailableException $exception) {
            Log::error('Elasticsearch anime index facets', [
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);

            return [];
        }
    }
}
