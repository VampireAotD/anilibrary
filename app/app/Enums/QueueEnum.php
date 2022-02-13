<?php

namespace App\Enums;

/**
 * Enum QueueEnum
 * @package App\Enums
 */
enum QueueEnum: string
{
    case ADD_ANIME_QUEUE = 'add-anime';
    case PICK_RANDOM_ANIME_QUEUE = 'random-anime';
    case ANIME_LIST_QUEUE = 'anime-list';
}
