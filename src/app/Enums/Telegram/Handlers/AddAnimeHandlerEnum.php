<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Handlers;

/**
 * Enum AddAnimeHandlerEnum
 * @package App\Enums\Telegram
 */
enum AddAnimeHandlerEnum: string
{
    case PARSE_FAILED    = 'Не удалось спарсить тайтл, попробуйте ещё раз';
    case PARSE_HAS_ENDED = 'Парсинг тайла закончился';
    case VIEW_ANIME      = 'Просмотр';
}
