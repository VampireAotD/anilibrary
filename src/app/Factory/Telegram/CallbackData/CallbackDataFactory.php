<?php

declare(strict_types=1);

namespace App\Factory\Telegram\CallbackData;

use App\DTO\Factory\Telegram\CallbackData\CallbackDataDTO;
use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\DTO\Factory\Telegram\CallbackData\ViewAnimeCallbackDataDTO;
use App\Services\Telegram\HashService;

/**
 * Class CallbackDataFactory
 * @package App\Factory\Telegram\Callback
 */
final readonly class CallbackDataFactory
{
    public function __construct(private HashService $hashService)
    {
    }

    public function resolve(CallbackDataDTO $dto): string
    {
        $callback = sprintf('command=%s', $dto->queryType->value);

        return match (true) {
            $dto instanceof ViewAnimeCallbackDataDTO  => sprintf(
                '%s&animeId=%s',
                $callback,
                $this->hashService->encodeUuid($dto->animeId)
            ),
            $dto instanceof PaginationCallbackDataDTO => sprintf('%s&page=%s', $callback, $dto->page),
            default                                   => '',
        };
    }
}