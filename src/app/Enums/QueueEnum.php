<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum QueueEnum
 * @package App\Enums
 */
enum QueueEnum: string
{
    use CanProvideCasesValues;

    case MAIL_QUEUE                          = 'mail';
    case REGISTER_TELEGRAM_USER_QUEUE        = 'register';
    case UPSERT_ANIME_IN_ELASTICSEARCH_QUEUE = 'upsert-anime';
    case SCRAPE_ANIME_QUEUE                  = 'scrape-anime';
    case PUSHER_SCRAPER_RESPONSE_QUEUE       = 'pusher-scrape-response';
}
