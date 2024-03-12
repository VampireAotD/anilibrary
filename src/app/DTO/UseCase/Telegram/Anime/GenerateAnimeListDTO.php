<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Anime;

readonly class GenerateAnimeListDTO
{
    public function __construct(public int $page = 1)
    {
    }
}
