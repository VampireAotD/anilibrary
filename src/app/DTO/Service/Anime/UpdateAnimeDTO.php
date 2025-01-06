<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\DTO\Contracts\FromArray;
use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class UpdateAnimeDTO implements FromArray, Arrayable
{
    /**
     * @param array<array{url: string}>  $urls
     * @param array<array{name: string}> $synonyms
     * @param list<string>               $voiceActing Array of voice acting ids
     * @param list<string>               $genres      Array of genre ids
     */
    public function __construct(
        public ?string     $title = null,
        public ?TypeEnum   $type = null,
        public ?StatusEnum $status = StatusEnum::ANNOUNCE,
        public ?float      $rating = null,
        public ?int        $episodes = null,
        public ?int        $year = null,
        public array       $urls = [],
        public ?string     $image = null,
        public array       $synonyms = [],
        public array       $voiceActing = [],
        public array       $genres = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title      : $data['title'] ?? null,
            type       : TypeEnum::from($data['type'] ?? null),
            status     : StatusEnum::from($data['status'] ?? null),
            rating     : $data['rating'] ?? null,
            episodes   : $data['episodes'] ?? null,
            year       : isset($data['year']) ? (int) $data['year'] : null,
            urls       : $data['urls'] ?? [],
            image      : $data['image'] ?? null,
            synonyms   : $data['synonyms'] ?? [],
            voiceActing: $data['voice_acting'] ?? [],
            genres     : $data['genres'] ?? [],
        );
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'title'    => $this->title,
            'type'     => $this->type,
            'status'   => $this->status,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
            'year'     => $this->year,
        ]);
    }
}
