<?php

namespace App\Dto\Parsers;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class ParseInitialDataDto
 * @package App\Dto\Parsers
 */
class ParseInitialDataDto implements Arrayable
{
    public function __construct(
        public readonly string $url,
        public readonly string $title,
        public readonly array $voiceActing,
        public readonly string $image,
        public readonly string $status,
        public readonly float $rating,
        public readonly string $episodes,
        public readonly array $genres,
        public readonly ?int $telegramId,
    ) {
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'url'         => "string",
        'title'       => "string",
        'voiceActing' => "array",
        'image'       => "string",
        'status'      => "string",
        'rating'      => "float",
        'episodes'    => "string",
        'genres'      => "array",
        'telegramId'  => "int|null",
    ])]
    public function toArray(): array
    {
        return [
            'url'         => $this->url,
            'title'       => $this->title,
            'voiceActing' => $this->voiceActing,
            'image'       => $this->image,
            'status'      => $this->status,
            'rating'      => $this->rating,
            'episodes'    => $this->episodes,
            'genres'      => $this->genres,
            'telegramId'  => $this->telegramId,
        ];
    }
}
