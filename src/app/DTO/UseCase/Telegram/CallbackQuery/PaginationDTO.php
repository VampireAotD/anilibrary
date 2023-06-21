<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\CallbackQuery;

use App\Enums\Telegram\CallbackQueryTypeEnum;

/**
 * Class PaginationDTO
 * @package App\DTO\UseCase\CallbackQuery
 */
final readonly class PaginationDTO extends CallbackQueryDTO
{
    public function __construct(
        int                   $chatId,
        public int            $page = 1,
        CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::ANIME_LIST
    ) {
        parent::__construct($chatId, $queryType);
    }
}
