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
use App\ValueObject\Telegram\Anime\AnimeCaptionText;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class AnimeMessageService
{
    public function __construct(private CallbackDataFactory $callbackDataFactory)
    {
    }

    public function createMessage(Anime $anime): AnimeMessageDTO
    {
        return new AnimeMessageDTO(
            $anime->image->path,
            (string) AnimeCaptionText::fromAnime($anime),
            $this->generateExternalLinks($anime)
        );
    }

    /**
     * @param LengthAwarePaginator<Anime> $paginator
     */
    public function createMessageWithPagination(
        LengthAwarePaginator $paginator,
        CallbackDataTypeEnum $queryType
    ): AnimeMessageDTO {
        $anime = $paginator->first();

        $buttons = [$this->generateExternalLinks($anime), $this->generatePagination($paginator, $queryType)];

        return new AnimeMessageDTO(
            $anime->image->path,
            (string) AnimeCaptionText::fromAnime($anime),
            $buttons
        );
    }

    /**
     * @return list<InlineKeyboardButtonDTO>
     */
    private function generateExternalLinks(Anime $anime): array
    {
        return $anime->urls->map(
            static fn(AnimeUrl $url) => new InlineKeyboardButtonDTO($url->domain, $url->url)
        )->toArray();
    }

    /**
     * @param LengthAwarePaginator<Anime> $paginator
     * @return list<InlineKeyboardButtonDTO>
     */
    private function generatePagination(
        LengthAwarePaginator $paginator,
        CallbackDataTypeEnum $queryType
    ): array {
        $pagination = [];

        if ($paginator->previousPageUrl()) {
            $pagination[] = new InlineKeyboardButtonDTO(
                text        : '<',
                callbackData: $this->callbackDataFactory->resolve(
                    new PaginationCallbackDataDTO($paginator->currentPage() - 1, $queryType)
                )
            );
        }

        if ($paginator->hasMorePages()) {
            $pagination[] = new InlineKeyboardButtonDTO(
                text        : '>',
                callbackData: $this->callbackDataFactory->resolve(
                    new PaginationCallbackDataDTO($paginator->currentPage() + 1, $queryType)
                )
            );
        }

        return $pagination;
    }
}
