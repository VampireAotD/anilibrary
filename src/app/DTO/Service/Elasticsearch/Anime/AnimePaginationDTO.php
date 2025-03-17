<?php

declare(strict_types=1);

namespace App\DTO\Service\Elasticsearch\Anime;

final readonly class AnimePaginationDTO
{
    public const int DEFAULT_PAGE = 1;

    public const int DEFAULT_PAGE_SIZE = 20;

    /**
     * @param array<string>         $fields
     * @param array<string, mixed>  $filters
     * @param array<string, string> $sort
     */
    public function __construct(
        public int   $page = self::DEFAULT_PAGE,
        public int   $perPage = self::DEFAULT_PAGE_SIZE,
        public array $fields = [],
        public array $filters = [],
        public array $sort = []
    ) {
    }

    /**
     * Returns array of mapped filters for Elasticsearch query.
     *
     * @return array<array{
     *     range?: array{
     *         year?: array{gte: int, lte: int},
     *     },
     *     terms?: array{
     *         type?: array<string>,
     *         status?: array<string>,
     *         genres?: array<string>,
     *         voice_acting?: array<string>,
     *     }
     * }>
     */
    public function getMappedFilters(): array
    {
        $mappedFilters = [];

        if (isset($this->filters['years'])) {
            $mappedFilters[]['range']['year'] = [
                'gte' => $this->filters['years']['min'],
                'lte' => $this->filters['years']['max'],
            ];
        }

        if (isset($this->filters['types'])) {
            $mappedFilters[]['terms']['type'] = $this->filters['types'];
        }

        if (isset($this->filters['statuses'])) {
            $mappedFilters[]['terms']['status'] = $this->filters['statuses'];
        }

        if (isset($this->filters['genres'])) {
            $mappedFilters[]['terms']['genres.name'] = $this->filters['genres'];
        }

        if (isset($this->filters['voiceActing'])) {
            $mappedFilters[]['terms']['voice_acting.name'] = $this->filters['voiceActing'];
        }

        return $mappedFilters;
    }
}
