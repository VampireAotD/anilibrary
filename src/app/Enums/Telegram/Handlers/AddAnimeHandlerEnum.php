<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Handlers;

/**
 * Enum AddAnimeHandlerEnum
 * @package App\Enums\Telegram
 */
enum AddAnimeHandlerEnum : string
{
    case PROVIDE_URL                = 'Укажите ссылку на тайтл';
    case PARSE_STARTED              = 'Начался парсинг тайтла, это может занять несколько минут';
    case PARSE_FAILED               = 'Не удалось спарсить тайтл, попробуйте ещё раз';
    case PARSE_HAS_ENDED            = 'Парсинг тайла закончился';
    case WATCH_RECENTLY_ADDED_ANIME = 'Просмотр';
}
