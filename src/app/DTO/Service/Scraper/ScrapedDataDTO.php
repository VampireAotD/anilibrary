<?php

declare(strict_types=1);

namespace App\DTO\Service\Scraper;

use App\Rules\Scraper\EncodedImageRule;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

/**
 * Class ScrapedDataDTO
 * @template-implements Arrayable<string, mixed>
 * @package App\DTO\Service\Scraper
 */
readonly class ScrapedDataDTO implements Arrayable
{
    public ?string $image;
    public ?int    $telegramId;

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
        ?int          $telegramId = null,
    ) {
        $this->image      = $image ?? config('cloudinary.default_image');
        $this->telegramId = $telegramId ?? config('admin.id');
    }

    /**
     * @return bool
     */
    public function hasValidData(): bool
    {
        return Validator::make(
            $this->toArray(),
            [
                'title' => 'required|string',
                'image' => ['nullable', 'string', new EncodedImageRule()],
            ]
        )->passes();
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
            'telegramId'  => $this->telegramId,
        ];
    }
}
