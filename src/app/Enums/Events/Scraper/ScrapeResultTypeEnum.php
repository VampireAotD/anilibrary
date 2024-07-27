<?php

declare(strict_types=1);

namespace App\Enums\Events\Scraper;

enum ScrapeResultTypeEnum: string
{
    case SUCCESS = 'success';
    case ERROR   = 'error';
}
