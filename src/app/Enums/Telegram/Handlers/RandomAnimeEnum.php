<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Handlers;

/**
 * Enum RandomAnimeEnum
 * @package App\Enums\Telegram
 */
enum RandomAnimeEnum : string
{
    case UNABLE_TO_FIND_ANIME = "К сожалению сейчас бот не содержит информацию ни об одном аниме \xF0\x9F\x98\xAD";
}
