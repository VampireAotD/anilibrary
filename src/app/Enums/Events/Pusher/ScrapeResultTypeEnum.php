<?php

declare(strict_types=1);

namespace App\Enums\Events\Pusher;

enum ScrapeResultTypeEnum: string
{
    case SUCCESS = 'success';
    case ERROR   = 'error';
}
