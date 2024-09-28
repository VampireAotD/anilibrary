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
final readonly class UpsertAnimeDTO implements Arrayable, FromArray
{
    public const int      DEFAULT_EPISODES = 0;
    public const float    DEFAULT_RATING   = 0.0;

    /**
     * @param array<array{url: string}>  $urls
     * @param array<array{name: string}> $synonyms
     * @param array<string>              $voiceActing Array of voice acting ids
     * @param array<string>              $genres      Array of genre ids
     */
    public function __construct(
        public string     $title,
        public TypeEnum   $type,
        public int        $year,
        public array      $urls,
        public StatusEnum $status = StatusEnum::ANNOUNCE,
        public float      $rating = self::DEFAULT_RATING,
        public int        $episodes = self::DEFAULT_EPISODES,
        public ?string    $image = null,
        public array      $synonyms = [],
        public array      $voiceActing = [],
        public array      $genres = [],
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
            'year'     => $this->year,
            'status'   => $this->status->value,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            TypeEnum::from($data['type']),
            (int) $data['year'],
            $data['urls'],
            StatusEnum::tryFrom($data['status'] ?? '') ?? StatusEnum::ANNOUNCE->value,
            $data['rating'] ?? self::DEFAULT_RATING,
            $data['episodes'] ?? self::DEFAULT_EPISODES,
            $data['image'] ?? null,
            $data['synonyms'] ?? [],
            $data['voice_acting'] ?? [],
            $data['genres'] ?? [],
        );
    }
}
