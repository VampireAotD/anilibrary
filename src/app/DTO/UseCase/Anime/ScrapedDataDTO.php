<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Anime;

use App\DTO\Contract\Common\FromArray;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ScrapedDataDTO
 * @package App\DTO\Service\Anime
 */
class ScrapedDataDTO implements Arrayable
{
    public function __construct(
        public readonly string $url,
        public readonly string $image,
        public readonly string $title,
        public readonly string $status,
        public readonly string $episodes,
        public readonly array  $genres,
        public readonly array  $voiceActing,
        public readonly float  $rating,
        public readonly ?int   $telegramId = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'url'         => $this->url,
            'image'       => $this->image,
            'title'       => $this->title,
            'status'      => $this->status,
            'episodes'    => $this->episodes,
            'genres'      => $this->genres,
            'voiceActing' => $this->voiceActing,
            'rating'      => $this->rating,
            'telegramId'  => $this->telegramId,
        ];
    }
}
