<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum QueueEnum
 * @package App\Enums
 */
enum QueueEnum : string
{
    case ADD_ANIME_QUEUE         = 'add-anime';
    case PICK_RANDOM_ANIME_QUEUE = 'random-anime';
    case ANIME_LIST_QUEUE        = 'anime-list';
    case MAIL_QUEUE              = 'mail';
    case EMPTY_ANIME_DATABASE    = "К сожалению сейчас бот не содержит информацию ни об одном аниме \xF0\x9F\x98\xAD";
}
