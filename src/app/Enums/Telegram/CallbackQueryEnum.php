<?php

declare(strict_types=1);

namespace App\Enums\Telegram;

/**
 * Enum CallbackQueryEnum
 * @package App\Enums\Telegram
 */
enum CallbackQueryEnum : string
{
    case CHECK_ADDED_ANIME = 'Check anime';
    case PAGINATION        = 'Pagination';
}
