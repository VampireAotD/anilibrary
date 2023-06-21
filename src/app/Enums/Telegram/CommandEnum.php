<?php

declare(strict_types=1);

namespace App\Enums\Telegram;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum CommandEnum
 * @package App\Enums\Telegram
 */
enum CommandEnum : string
{
    use CanProvideCasesValues;

    case ADD_ANIME_BUTTON    = 'Добавить аниме';
    case RANDOM_ANIME_BUTTON = 'Случайное аниме';
    case ANIME_LIST_BUTTON   = 'Список аниме';

    case ADD_NEW_TITLE_COMMAND = '/add';
    case RANDOM_ANIME_COMMAND  = '/random';
    case ANIME_LIST_COMMAND    = '/list';
}
