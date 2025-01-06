<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Actions;

/**
 * Used for Nutgram, because it can only register handler triggers without slash symbol
 */
enum ActionEnum: string
{
    case START_COMMAND                   = 'start';
    case RANDOM_ANIME_COMMAND            = 'random';
    case ANIME_LIST_COMMAND              = 'list';
    case START_ANIME_CONVERSATION        = 'add';
    case START_SEARCH_ANIME_CONVERSATION = 'search';
}
