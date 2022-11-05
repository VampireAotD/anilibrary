<?php

declare(strict_types=1);

namespace App\DTO\Handlers;

use App\Enums\Telegram\CallbackQueryEnum;

/**
 * Class CallbackQueryDTO
 * @package App\DTO\Handlers
 */
class CallbackQueryDTO
{
    /**
     * @param CallbackQueryEnum $option
     * @param string|null       $animeId
     * @param int               $pageNumber
     */
    public function __construct(
        public readonly CallbackQueryEnum $option,
        public readonly ?string           $animeId = null,
        public readonly int               $pageNumber = 1
    ) {
    }
}
