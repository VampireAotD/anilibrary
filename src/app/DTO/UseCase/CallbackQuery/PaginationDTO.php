<?php

declare(strict_types=1);

namespace App\DTO\UseCase\CallbackQuery;

/**
 * Class PaginationDTO
 * @package App\DTO\UseCase\CallbackQuery
 */
class PaginationDTO
{
    public function __construct(
        public readonly int $chatId,
        public readonly int $page = 1
    ) {
    }
}
