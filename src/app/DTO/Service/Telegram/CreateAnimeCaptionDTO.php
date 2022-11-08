<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram;

use App\Models\Anime;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CreateAnimeCaptionDTO
 * @package App\DTO\Service\Telegram
 */
class CreateAnimeCaptionDTO
{
    public function __construct(
        public readonly Anime                 $anime,
        public readonly int                   $chatId,
        public readonly ?LengthAwarePaginator $paginator = null
    ) {
    }
}
