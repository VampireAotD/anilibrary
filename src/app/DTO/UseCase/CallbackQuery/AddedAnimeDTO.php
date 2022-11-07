<?php

declare(strict_types=1);

namespace App\DTO\UseCase\CallbackQuery;

/**
 * Class AddedAnimeDTO
 * @package App\DTO\UseCase\CallbackQuery
 */
class AddedAnimeDTO
{
    public function __construct(
        public readonly string $encodedId,
        public readonly int    $chatId
    ) {
    }
}
