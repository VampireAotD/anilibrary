<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Handlers\CallbackQueryDTO;
use App\Enums\Telegram\CallbackQueryEnum;

/**
 * Class CallbackQueryService
 * @package App\Services
 */
class CallbackQueryService
{
    public function __construct(private readonly HashIdService $hashIdService)
    {
    }

    /**
     * @param CallbackQueryDTO $callbackQueryDTO
     * @return string
     */
    public function create(CallbackQueryDTO $callbackQueryDTO): string
    {
        return match ($callbackQueryDTO->option) {
            CallbackQueryEnum::CHECK_ADDED_ANIME => sprintf(
                'command=%s&animeId=%s',
                CallbackQueryEnum::CHECK_ADDED_ANIME->value,
                $this->hashIdService->encode($callbackQueryDTO->animeId),
            ),
            CallbackQueryEnum::PAGINATION        => sprintf(
                'command=%s&page=%d',
                CallbackQueryEnum::PAGINATION->value,
                $callbackQueryDTO->pageNumber
            ),
            default                              => ''
        };
    }
}
