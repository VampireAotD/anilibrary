<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Callbacks;

/**
 * Enum CallbackQueryTypeEnum
 * @package App\Enums\Telegram
 */
enum CallbackQueryTypeEnum : string
{
    case VIEW_ANIME  = 'view-anime';
    case ANIME_LIST  = 'anime-list';
    case SEARCH_LIST = 'search-list';
}