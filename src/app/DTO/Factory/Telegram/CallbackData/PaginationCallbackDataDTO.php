<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;

/**
 * Class PaginationCallbackDataDTO
 * @package App\DTO\Factory\Telegram\CallbackData
 */
final readonly class PaginationCallbackDataDTO extends CallbackDataDTO
{
    public function __construct(
        public int            $page = 1,
        CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::ANIME_LIST
    ) {
        parent::__construct($queryType);
    }
}
