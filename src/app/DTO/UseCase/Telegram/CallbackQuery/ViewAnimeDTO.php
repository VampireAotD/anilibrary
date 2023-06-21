<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Telegram\CallbackQuery;

use App\Enums\Telegram\CallbackQueryTypeEnum;

/**
 * Class ViewAnimeDTO
 * @package App\DTO\UseCase\CallbackQuery
 */
final readonly class ViewAnimeDTO extends CallbackQueryDTO
{
    public function __construct(
        public string         $encodedId,
        int                   $chatId,
        CallbackQueryTypeEnum $queryType = CallbackQueryTypeEnum::VIEW_ANIME
    ) {
        parent::__construct($chatId, $queryType);
    }
}
