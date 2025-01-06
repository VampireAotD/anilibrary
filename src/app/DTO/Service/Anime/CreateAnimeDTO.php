<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class CreateAnimeDTO implements Arrayable
{
    /**
     * @param array<array{url: string}>  $urls
     * @param array<array{name: string}> $synonyms
     * @param list<string>               $voiceActing Array of voice acting ids
     * @param list<string>               $genres      Array of genre ids
     */
    public function __construct(
        public string      $title,
        public int         $year,
        public array       $urls,
        public ?TypeEnum   $type = TypeEnum::SHOW,
        public ?StatusEnum $status = StatusEnum::ANNOUNCE,
        public float       $rating = 0.0,
        public int         $episodes = 0,
        public ?string     $image = null,
        public array       $synonyms = [],
        public array       $voiceActing = [],
        public array       $genres = [],
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
            'title'    => $this->title,
            'type'     => $this->type,
            'status'   => $this->status,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
            'year'     => $this->year,
        ];
    }
}
