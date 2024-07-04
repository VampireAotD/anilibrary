<?php

declare(strict_types=1);

namespace App\DTO\Service\Scraper;

use App\DTO\Contracts\FromArray;
use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class ScrapedDataDTO implements Arrayable, FromArray
{
    public function __construct(
        public string     $url,
        public string     $title,
        public TypeEnum   $type,
        public StatusEnum $status,
        public string     $episodes,
        public float      $rating,
        public int        $year,
        public array      $genres = [],
        public array      $voiceActing = [],
        public array      $synonyms = [],
        public ?string    $image = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['url'],
            $data['title'],
            TypeEnum::from($data['type']),
            StatusEnum::from($data['status']),
            $data['episodes'],
            $data['rating'],
            (int) $data['year'],
            $data['genres'] ?? [],
            $data['voiceActing'] ?? [],
            $data['synonyms'] ?? [],
            $data['image'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'url'         => $this->url,
            'title'       => $this->title,
            'status'      => $this->status,
            'episodes'    => $this->episodes,
            'genres'      => $this->genres,
            'voiceActing' => $this->voiceActing,
            'rating'      => $this->rating,
            'image'       => $this->image,
        ];
    }
}
