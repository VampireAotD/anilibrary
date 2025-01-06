<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;

final readonly class PaginationCallbackDataDTO extends CallbackDataDTO
{
    public function __construct(
        public int           $page = 1,
        CallbackDataTypeEnum $queryType = CallbackDataTypeEnum::ANIME_LIST
    ) {
        parent::__construct($queryType);
    }
}
