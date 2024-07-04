<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\Enums\Anime\TypeEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class FindSimilarAnimeDTO implements Arrayable
{
    /**
     * @param array<string> $titles
     */
    public function __construct(
        public array    $titles,
        public TypeEnum $type,
        public int      $year,
    ) {
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'titles' => $this->titles,
            'type'   => $this->type->value,
            'year'   => $this->year,
        ];
    }
}
