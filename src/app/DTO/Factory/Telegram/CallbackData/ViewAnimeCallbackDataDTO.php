<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;

final readonly class ViewAnimeCallbackDataDTO extends CallbackDataDTO
{
    public function __construct(
        public string        $animeId,
        CallbackDataTypeEnum $queryType = CallbackDataTypeEnum::VIEW_ANIME
    ) {
        parent::__construct($queryType);
    }
}
