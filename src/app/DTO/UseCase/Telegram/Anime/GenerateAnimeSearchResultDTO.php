<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Anime;

final readonly class GenerateAnimeSearchResultDTO
{
    public function __construct(public int $userId, public int $page = 1)
    {
    }
}
