<?php

declare(strict_types=1);

namespace App\ValueObject\Telegram\Anime;

use App\Models\Anime;
use Stringable;

final readonly class AnimeCaptionText implements Stringable
{
    public function __construct(public Anime $anime)
    {
    }

    public static function fromAnime(Anime $anime): self
    {
        return new self($anime);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return __('telegram.captions.anime', [
            'title'       => $this->anime->title,
            'status'      => $this->anime->status->value, // @phpstan-ignore-line Ignored because of parser issues
            'episodes'    => $this->anime->episodes,
            'rating'      => $this->anime->rating,
            'voiceacting' => $this->anime->voiceActing->implode('name', ', '),
            'genres'      => $this->anime->genres->implode('name', ', '),
        ]);
    }
}
