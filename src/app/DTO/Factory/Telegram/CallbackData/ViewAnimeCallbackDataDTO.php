<?php

declare(strict_types=1);

namespace App\DTO\Factory\Telegram\CallbackData;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;

/**
 * Class ViewAnimeCallbackDataDTO
 * @package App\DTO\Factory\Telegram\CallbackData
 */
final readonly class ViewAnimeCallbackDataDTO extends CallbackDataDTO
{
    public function __construct(
        public string         $animeId,
        CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::VIEW_ANIME
    ) {
        parent::__construct($queryType);
    }
}
