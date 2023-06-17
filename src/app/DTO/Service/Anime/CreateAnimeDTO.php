<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class CreateAnimeDTO
 * @template-implements Arrayable<string, string|float>
 * @package App\DTO\Service\Anime
 */
readonly class CreateAnimeDTO implements Arrayable
{
    public function __construct(
        public string $title,
        public string $status,
        public float  $rating,
        public string $episodes,
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
