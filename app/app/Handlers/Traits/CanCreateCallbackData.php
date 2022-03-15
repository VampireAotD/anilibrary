<?php

namespace App\Handlers\Traits;

use App\Dto\Handlers\CallbackDataDto;
use App\Enums\CallbackQueryEnum;

/**
 * Trait CanCreateCallbackData
 * @package Handlers\Traits
 */
trait CanCreateCallbackData
{
    use CanResolveIdHash;

    /**
     * @param CallbackQueryEnum $callbackQueryEnum
     * @param CallbackDataDto $callbackDataDto
     * @return string
     */
    public function createCallbackData(
        CallbackQueryEnum $callbackQueryEnum,
        CallbackDataDto $callbackDataDto
    ): string {
        return match ($callbackQueryEnum) {
            CallbackQueryEnum::CHECK_ADDED_ANIME => sprintf(
                'command=%s&animeId=%s',
                CallbackQueryEnum::CHECK_ADDED_ANIME->value,
                $this->encode($callbackDataDto->animeId),
            ),
            CallbackQueryEnum::PAGINATION => sprintf(
                'command=%s&page=%d',
                CallbackQueryEnum::PAGINATION->value,
                $callbackDataDto->pageNumber
            ),
            default => ''
        };
    }
}
