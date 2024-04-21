<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Factory\Telegram\CallbackData\PaginationCallbackDataDTO;
use App\DTO\Service\Telegram\Anime\AnimeMessageDTO;
use App\DTO\Service\Telegram\Keyboard\InlineKeyboardButtonDTO;
use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;
use App\Factory\Telegram\CallbackData\CallbackDataFactory;
use App\Models\Anime;
use App\Models\AnimeUrl;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class AnimeMessageService
{
    public function __construct(private CallbackDataFactory $callbackDataFactory)
    {
    }

    public function createMessage(Anime $anime): AnimeMessageDTO
    {
        $buttons = $anime->urls->map(function (AnimeUrl $url) {
            return new InlineKeyboardButtonDTO($url->domain, $url->url);
        })->toArray();

        // Suppressing PHPStan's false positive on `to_telegram_caption` which it mistakenly flags as undefined property.
        /** @phpstan-ignore-next-line */
        return new AnimeMessageDTO($anime->image->path, $anime->to_telegram_caption, $buttons);
    }

    public function createMessageWithPagination(
        LengthAwarePaginator $paginator,
        CallbackDataTypeEnum $queryType
    ): AnimeMessageDTO {
        $message = $this->createMessage($paginator->first());

        if ($paginator->previousPageUrl()) {
            $message->addButton(
                new InlineKeyboardButtonDTO(
                    text        : '<',
                    callbackData: $this->callbackDataFactory->resolve(
                        new PaginationCallbackDataDTO($paginator->currentPage() - 1, $queryType)
                    ),
                )
            );
        }

        if ($paginator->hasMorePages()) {
            $message->addButton(
                new InlineKeyboardButtonDTO(
                    text        : '>',
                    callbackData: $this->callbackDataFactory->resolve(
                        new PaginationCallbackDataDTO($paginator->currentPage() + 1, $queryType)
                    ),
                )
            );
        }

        return $message;
    }
}
