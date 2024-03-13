<?php

declare(strict_types=1);

namespace App\DTO\Events\Pusher;

use App\Enums\Events\Pusher\ScrapeResultTypeEnum;

final readonly class ScrapeResultDTO
{
    public function __construct(
        public string               $userId,
        public ScrapeResultTypeEnum $resultType,
        public string               $message
    ) {
    }
}
