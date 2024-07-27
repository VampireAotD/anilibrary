<?php

declare(strict_types=1);

namespace App\DTO\Events\Scraper;

use App\Enums\Events\Scraper\ScrapeResultTypeEnum;

final readonly class ScrapeAnimeResultDTO
{
    public function __construct(
        public string               $userId,
        public ScrapeResultTypeEnum $resultType,
        public string               $message
    ) {
    }
}
