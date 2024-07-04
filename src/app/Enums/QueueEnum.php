<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\ProvideValues;

enum QueueEnum: string
{
    use ProvideValues;

    case MAIL_QUEUE          = 'mail';
    case PUSHER_QUEUE        = 'pusher';
    case SCRAPER_QUEUE       = 'scraper';
    case TELEGRAM_QUEUE      = 'telegram';
    case ELASTICSEARCH_QUEUE = 'elasticsearch';
    case IMAGE_STORAGE_QUEUE = 'image-storage';
}
