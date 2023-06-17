<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram;

use App\Models\Anime;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CreateAnimeCaptionDTO
 * @package App\DTO\Service\Telegram
 */
readonly class CreateAnimeCaptionDTO
{
    public function __construct(
        public Anime                 $anime,
        public int                   $chatId,
        public ?LengthAwarePaginator $paginator = null
    ) {
    }
}
