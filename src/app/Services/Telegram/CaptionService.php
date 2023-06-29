<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\DTO\Service\Telegram\Caption\CaptionDTO;
use App\DTO\Service\Telegram\Caption\PaginationCaptionDTO;
use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\Models\AnimeUrl;

/**
 * Class CaptionService
 * @package App\Services\Telegram
 */
readonly class CaptionService
{
    public function __construct(private CallbackDataFactory $callbackDataFactory)
    {
    }

    public function create(CaptionDTO $dto): array
    {
        switch ($dto) {
            case $dto instanceof ViewAnimeCaptionDTO:
                $caption = $this->createAnimeCaption($dto->anime);
                break;
            case $dto instanceof PaginationCaptionDTO:
                /** @psalm-suppress NoValue */
                $caption = $this->createPaginationCaption($dto);
                break;
            default:
                return [];
        }

        return $caption + ['chat_id' => $dto->chatId];
    }

    /**
     * @param Anime $anime
     * @return array
     */
    private function createAnimeCaption(Anime $anime): array
    {
        $keyboard = $anime->urls->map(
            fn(AnimeUrl $animeUrl) => $animeUrl->toTelegramKeyboardButton
        )->toArray();

        return [
            'caption'      => $anime->toTelegramCaption,
            'photo'        => $anime->image->path,
            'reply_markup' => [
                'inline_keyboard' => [$keyboard],
            ],
        ];
    }

    /**
     * @param PaginationCaptionDTO $dto
     * @return array
     */
    private function createPaginationCaption(PaginationCaptionDTO $dto): array
    {
        if ($dto->paginator->isEmpty()) {
            return [];
        }

        $caption  = $this->createAnimeCaption($dto->paginator->first());
        $controls = [];

        if ($dto->paginator->previousPageUrl()) {
            $controls[] = [
                'text'          => '<',
                'callback_data' => $this->callbackDataFactory->resolve(
                    new PaginationCallbackDataDTO($dto->paginator->currentPage() - 1, $dto->queryType)
                ),
            ];
        }

        if ($dto->paginator->hasMorePages()) {
            $controls[] = [
                'text'          => '>',
                'callback_data' => $this->callbackDataFactory->resolve(
                    new PaginationCallbackDataDTO($dto->paginator->currentPage() + 1, $dto->queryType)
                ),
            ];
        }

        $caption['reply_markup']['inline_keyboard'][] = $controls;

        return $caption;
    }
}
