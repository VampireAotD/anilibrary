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
final readonly class AnimeDTO implements Arrayable, FromArray
{
    /**
     * @param array<array{url: string}>  $urls
     * @param array<array{name: string}> $synonyms
     * @param array<string>              $voiceActing Array of voice acting ids
     * @param array<string>              $genres      Array of genre ids
     */
    public function __construct(
        public string     $title,
        public TypeEnum   $type,
        public StatusEnum $status,
        public float      $rating,
        public int        $episodes,
        public int        $year,
        public array      $urls,
        public ?string    $image = null,
        public array      $synonyms = [],
        public array      $voiceActing = [],
        public array      $genres = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            TypeEnum::from($data['type']),
            StatusEnum::from($data['status']),
            $data['rating'],
            $data['episodes'],
            (int) $data['year'],
            $data['urls'],
            $data['image'] ?? null,
            $data['synonyms'] ?? [],
            $data['voice_acting'] ?? [],
            $data['genres'] ?? [],
        );
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
            'year'     => $this->year,
            'status'   => $this->status->value,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
        ];
    }
}
