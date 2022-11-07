<?php

declare(strict_types=1);

namespace App\DTO\Service\CallbackData;

use App\Enums\Telegram\CallbackQueryEnum;

/**
 * Class CreateCallbackDataDTO
 * @package App\DTO\Service\CallbackData
 */
class CreateCallbackDataDTO
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
