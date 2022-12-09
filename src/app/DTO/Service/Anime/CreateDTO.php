<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class CreateDTO
 * @package App\DTO\Service\Anime
 */
class CreateDTO implements Arrayable
{
    public function __construct(
        public readonly string $title,
        public readonly string $status,
        public readonly float  $rating,
        public readonly string $episodes,
    ) {
    }

    /**
     * @return array<string, string|float>
     */
    public function toArray(): array
    {
        return [
            'title'    => $this->title,
            'status'   => $this->status,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
        ];
    }
}
