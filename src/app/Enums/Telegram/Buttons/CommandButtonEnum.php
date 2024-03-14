<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Buttons;

use App\Enums\Concerns\ProvideValues;

enum CommandButtonEnum: string
{
    use ProvideValues;

    case ADD_ANIME_BUTTON    = 'Add anime';
    case RANDOM_ANIME_BUTTON = 'Random anime';
    case ANIME_LIST_BUTTON   = 'Anime list';
    case ANIME_SEARCH_BUTTON = 'Search anime';
}
