<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Anime;

final readonly class GenerateAnimeMessageDTO
{
    public function __construct(public string $animeId, public bool $idEncoded = false)
    {
    }
}
