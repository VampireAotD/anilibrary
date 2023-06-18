<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Service\CallbackData\CreateCallbackDataDTO;
use App\Enums\Telegram\CallbackQueryEnum;

/**
 * Class CallbackDataService
 * @package App\Services\Telegram
 */
readonly class CallbackDataService
{
    public function __construct(private Base62Service $base62Service)
    {
    }

    /**
     * @param CreateCallbackDataDTO $callbackDataDTO
     * @return string
     */
    public function create(CreateCallbackDataDTO $callbackDataDTO): string
    {
        return match ($callbackDataDTO->option) {
            CallbackQueryEnum::CHECK_ADDED_ANIME => sprintf(
                'command=%s&animeId=%s',
                CallbackQueryEnum::CHECK_ADDED_ANIME->value,
                $this->base62Service->encode($callbackDataDTO->animeId),
            ),
            CallbackQueryEnum::PAGINATION        => sprintf(
                'command=%s&page=%d',
                CallbackQueryEnum::PAGINATION->value,
                $callbackDataDTO->pageNumber
            ),
        };
    }
}
