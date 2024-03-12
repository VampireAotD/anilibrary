<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Anime;

final readonly class GenerateAnimeSearchResultDTO extends GenerateAnimeListDTO
{
    public function __construct(public int $userId, int $page = 1)
    {
        parent::__construct($page);
    }
}
