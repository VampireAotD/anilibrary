<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\DTO\Contracts\FromArray;
use App\Enums\AnimeStatusEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class UpsertAnimeDTO
 * @package App\DTO\Service\Anime
 * @template-implements Arrayable<string, mixed>
 */
final readonly class UpsertAnimeDTO implements Arrayable, FromArray
{
    public const DEFAULT_RATING   = 0;
    public const DEFAULT_EPISODES = '0 / ?';

    public function __construct(
        public string          $title,
        public AnimeStatusEnum $status = AnimeStatusEnum::ANNOUNCE,
        public float           $rating = self::DEFAULT_RATING,
        public string          $episodes = self::DEFAULT_EPISODES,
        public array           $urls = [],
        public array           $synonyms = [],
        public array           $voiceActing = [],
        public array           $genres = [],
        public ?string         $image = null
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
            'title'        => $this->title,
            'status'       => $this->status->value,
            'episodes'     => $this->episodes,
            'rating'       => $this->rating,
            'urls'         => $this->urls,
            'synonyms'     => $this->synonyms,
            'voice_acting' => $this->voiceActing,
            'genres'       => $this->genres,
            'image'        => $this->image,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): FromArray
    {
        return new self(
            $data['title'],
            AnimeStatusEnum::tryFrom($data['status'] ?? '') ?? AnimeStatusEnum::ANNOUNCE->value,
            $data['rating'] ?? self::DEFAULT_RATING,
            $data['episodes'] ?? self::DEFAULT_EPISODES,
            $data['urls'] ?? [],
            $data['synonyms'] ?? [],
            $data['voice_acting'] ?? [],
            $data['genres'] ?? [],
        );
    }
}
