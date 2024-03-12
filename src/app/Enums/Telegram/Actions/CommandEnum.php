<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Actions;

use App\Enums\Traits\CanProvideCasesValues;

enum CommandEnum: string
{
    use CanProvideCasesValues;

    case START_COMMAND        = '/start';
    case ADD_ANIME_COMMAND    = '/add';
    case RANDOM_ANIME_COMMAND = '/random';
    case ANIME_LIST_COMMAND   = '/list';
    case SEARCH_ANIME_COMMAND = '/search';
}
