<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\DTO\Contracts\FromArray;
use App\Enums\AnimeStatusEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class UpdateAnimeDTO
 * @template-implements Arrayable<string, mixed>
 * @package App\DTO\Service\Anime
 */
readonly class UpdateAnimeDTO implements Arrayable, FromArray
{
    public function __construct(
        public string          $id,
        public string          $title,
        public AnimeStatusEnum $status,
        public float           $rating,
        public string          $episodes,
        public array           $urls,
        public array           $synonyms,
        public array           $voiceActing,
        public array           $genres,
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
            'status'   => $this->status->value,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): UpdateAnimeDTO
    {
        return new self(
            $data['id'] ?? '',
            $data['title'] ?? '',
            AnimeStatusEnum::tryFrom($data['status'] ?? '') ?? AnimeStatusEnum::ANNOUNCE->value,
            $data['rating'] ?? 0,
            $data['episodes'] ?? '?',
            $data['urls'] ?? [],
            $data['synonyms'] ?? [],
            $data['voice_acting'] ?? [],
            $data['genres'] ?? [],
        );
    }
}
