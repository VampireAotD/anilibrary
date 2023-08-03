<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Callbacks;

/**
 * Enum ViewAnimeCallbackEnum
 * @package App\Enums\Telegram\Callbacks
 */
enum ViewAnimeCallbackEnum: string
{
    case FAILED_TO_GET_ANIME = 'Не удалось найти аниме';
}
