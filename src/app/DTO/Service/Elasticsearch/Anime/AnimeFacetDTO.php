<?php

declare(strict_types=1);

namespace App\DTO\Service\Elasticsearch\Anime;

use App\DTO\Contracts\FromArray;
use Illuminate\Contracts\Support\Arrayable;

final readonly class AnimeFacetDTO implements FromArray, Arrayable
{
    public function __construct(
        public array $years,
        public array $types,
        public array $statuses,
        public array $genres,
        public array $voiceActing
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $facets       = [];
        $aggregations = $data['aggregations'];

        $facets['years'] = [
            'min' => (int) $aggregations['min_year']['value'],
            'max' => (int) $aggregations['max_year']['value'],
        ];

        foreach ($aggregations as $facet => $aggregation) {
            if (isset($aggregation['buckets'])) {
                $facets[$facet] = array_reduce($aggregation['buckets'], function (array $accumulator, array $bucket) {
                    $accumulator[$bucket['key']] = $bucket['doc_count'];
                    return $accumulator;
                }, []);
            }
        }

        return new self(
            $facets['years'],
            $facets['types'],
            $facets['statuses'],
            $facets['genres'],
            $facets['voice_acting'],
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, array<string, int>>
     */
    public function toArray(): array
    {
        return [
            'years'       => $this->years,
            'types'       => $this->types,
            'statuses'    => $this->statuses,
            'genres'      => $this->genres,
            'voiceActing' => $this->voiceActing,
        ];
    }
}
