<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum QueueEnum
 * @package App\Enums
 */
enum QueueEnum: string
{
    case MAIL_QUEUE                          = 'mail';
    case REGISTER_TELEGRAM_USER_QUEUE        = 'register';
    case UPSERT_ANIME_IN_ELASTICSEARCH_QUEUE = 'upsert-anime';
}
