<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Callbacks;

enum CallbackDataTypeEnum: string
{
    case VIEW_ANIME  = 'view-anime';
    case ANIME_LIST  = 'anime-list';
    case SEARCH_LIST = 'search-list';
}
