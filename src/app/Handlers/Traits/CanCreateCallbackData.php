<?php

declare(strict_types=1);

namespace App\Handlers\Traits;

use App\DTO\Handlers\CallbackDataDTO;
use App\Enums\CallbackQueryEnum;

/**
 * Trait CanCreateCallbackData
 * @package App\Handlers\Traits
 */
trait CanCreateCallbackData
{
    use CanResolveIdHash;

    /**
     * @param CallbackQueryEnum $callbackQueryEnum
     * @param CallbackDataDTO   $callbackDataDTO
     * @return string
     */
    public function createCallbackData(
        CallbackQueryEnum $callbackQueryEnum,
        CallbackDataDTO   $callbackDataDTO
    ): string {
        return match ($callbackQueryEnum) {
            CallbackQueryEnum::CHECK_ADDED_ANIME => sprintf(
                'command=%s&animeId=%s',
                CallbackQueryEnum::CHECK_ADDED_ANIME->value,
                $this->encode($callbackDataDTO->animeId),
            ),
            CallbackQueryEnum::PAGINATION        => sprintf(
                'command=%s&page=%d',
                CallbackQueryEnum::PAGINATION->value,
                $callbackDataDTO->pageNumber
            ),
            default                              => ''
        };
    }
}
