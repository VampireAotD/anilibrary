<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum CommandEnum
 * @package App\Enums
 */
enum CommandEnum: string
{
    use CanProvideCasesValues;

    case ADD_NEW_TITLE = "\xE2\x9E\x95 Добавить новое аниме";
    case RANDOM_ANIME = "\xE2\x81\x89 Рандомное аниме";
    case ANIME_LIST = "\xF0\x9F\x93\xBA Список всех тайтлов";

    case ADD_NEW_TITLE_COMMAND = '/add';
    case RANDOM_ANIME_COMMAND = '/random';
    case ANIME_LIST_COMMAND = '/list';
}
