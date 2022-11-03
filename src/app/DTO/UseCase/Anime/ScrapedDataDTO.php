<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Anime;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

/**
 * Class ScrapedDataDTO
 * @package App\DTO\Service\Anime
 */
class ScrapedDataDTO implements Arrayable
{
    public function __construct(
        public readonly string $url,
        public readonly string $status,
        public readonly string $episodes,
        public readonly float  $rating,
        public readonly string $title = '',
        public readonly array  $genres = [],
        public readonly array  $voiceActing = [],
        private ?string        $image = null,
        private ?int           $telegramId = null,
    ) {
        $this->image      ??= config('cloudinary.default_image');
        $this->telegramId ??= config('admin.id');
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return int|null
     */
    public function getTelegramId(): ?int
    {
        return $this->telegramId;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return Validator::make(
            $this->toArray(),
            [
                'title' => 'required|string',
                'image' => 'nullable|string|valid_image',
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
            'image'       => $this->getImage(),
            'telegramId'  => $this->getTelegramId(),
        ];
    }
}
