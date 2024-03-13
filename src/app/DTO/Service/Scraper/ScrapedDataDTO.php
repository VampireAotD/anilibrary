<?php

declare(strict_types=1);

namespace App\DTO\Service\Scraper;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class ScrapedDataDTO implements Arrayable
{
    public ?string $image;

    public function __construct(
        public string $url,
        public string $status,
        public string $episodes,
        public float  $rating,
        public string $title = '',
        public array  $genres = [],
        public array  $voiceActing = [],
        public array  $synonyms = [],
        ?string       $image = null,
    ) {
        $this->image = $image ?? config('cloudinary.default_image');
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
