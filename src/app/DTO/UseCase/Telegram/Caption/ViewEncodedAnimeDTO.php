<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\Caption;

use App\Enums\Telegram\Callbacks\CallbackQueryTypeEnum;

/**
 * Class ViewEncodedAnimeDTO
 * @package App\DTO\UseCase\Telegram\Caption
 */
final readonly class ViewEncodedAnimeDTO extends CallbackQueryDTO
{
    public function __construct(
        public string         $encodedId,
        int                   $chatId,
        CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::VIEW_ANIME
    ) {
        parent::__construct($chatId, $queryType);
    }
}
