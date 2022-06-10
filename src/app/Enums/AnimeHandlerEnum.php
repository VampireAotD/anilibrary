<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum AnimeHandlerEnum
 * @package App\Enums
 */
enum AnimeHandlerEnum: string
{
    case PROVIDE_URL = 'Укажите ссылку на тайтл';
    case STARTED_PARSE_MESSAGE = 'Начался парсинг тайтла, это может занять несколько минут';
    case PARSE_HAS_ENDED = 'Парсинг тайла закончился';
    case INVALID_URL = 'Ссылка оказалась невалидной, попробуйте ещё раз';
    case UNDEFINED_PARSER = 'Бот не поддерживает парсинг данного сайта';
    case WATCH_RECENTLY_ADDED_ANIME = 'Просмотр';
}
