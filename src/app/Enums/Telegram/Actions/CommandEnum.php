<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Actions;

use App\Enums\Concerns\ProvideValues;

enum CommandEnum: string
{
    use ProvideValues;

    case START_COMMAND        = '/start';
    case ADD_ANIME_COMMAND    = '/add';
    case RANDOM_ANIME_COMMAND = '/random';
    case ANIME_LIST_COMMAND   = '/list';
    case SEARCH_ANIME_COMMAND = '/search';
}
