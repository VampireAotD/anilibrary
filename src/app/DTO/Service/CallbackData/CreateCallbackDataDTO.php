<?php

declare(strict_types=1);

namespace App\DTO\Service\CallbackData;

use App\Enums\Telegram\CallbackQueryEnum;

/**
 * Class CreateCallbackDataDTO
 * @package App\DTO\Service\CallbackData
 */
readonly class CreateCallbackDataDTO
{
    /**
     * @param CallbackQueryEnum $option
     * @param string|null       $animeId
     * @param int               $pageNumber
     */
    public function __construct(
        public CallbackQueryEnum $option,
        public ?string           $animeId = null,
        public int               $pageNumber = 1
    ) {
    }
}
