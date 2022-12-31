<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Service\CallbackData\CreateCallbackDataDTO;
use App\DTO\Service\Telegram\CreateAnimeCaptionDTO;
use App\Enums\Telegram\AnimeCaptionEnum;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Models\AnimeUrl;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CaptionService
 * @package App\Services\Telegram
 */
class CaptionService
{
    public function __construct(private readonly CallbackDataService $callbackQueryService)
    {
    }

    /**
     * @param CreateAnimeCaptionDTO $dto
     * @return array
     */
    public function create(CreateAnimeCaptionDTO $dto): array
    {
        $anime = $dto->anime;

        $keyboard = $anime->urls->map(
            fn(AnimeUrl $animeUrl) => [
                'text' => AnimeCaptionEnum::LINK->value,
                'url'  => $animeUrl->url,
            ]
        )->toArray();

        $response = [
            'caption'      => $anime->caption,
            'photo'        => $anime->image->path,
            'reply_markup' => [
                'inline_keyboard' => [$keyboard],
            ],
            'chat_id'      => $dto->chatId,
        ];

        if ($dto->paginator) {
            $response['reply_markup']['inline_keyboard'][] = $this->generatePaginationMarkup($dto->paginator);
        }

        return $response;
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    private function generatePaginationMarkup(LengthAwarePaginator $paginator): array
    {
        $pages = [];

        if ($paginator->previousPageUrl()) {
            $pages[] = [
                'text'          => '<',
                'callback_data' => $this->callbackQueryService->create(
                    new CreateCallbackDataDTO(CallbackQueryEnum::PAGINATION, pageNumber: $paginator->currentPage() - 1),
                ),
            ];
        }

        if ($paginator->hasMorePages()) {
            $pages[] = [
                'text'          => '>',
                'callback_data' => $this->callbackQueryService->create(
                    new CreateCallbackDataDTO(CallbackQueryEnum::PAGINATION, pageNumber: $paginator->currentPage() + 1)
                ),
            ];
        }

        return $pages;
    }
}
