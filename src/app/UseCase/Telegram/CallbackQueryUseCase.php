<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\DTO\Service\Telegram\Caption\PaginationCaptionDTO;
use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\DTO\UseCase\Telegram\CallbackQuery\PaginationDTO;
use App\DTO\UseCase\Telegram\CallbackQuery\ViewAnimeDTO;
use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\Base62Service;
use App\Services\Telegram\CaptionService;

/**
 * Class CallbackQueryUseCase
 * @package App\UseCase
 */
readonly class CallbackQueryUseCase
{
    public function __construct(
        private AnimeRepositoryInterface $animeRepository,
        private Base62Service            $base62Service,
        private CaptionService           $captionService
    ) {
    }

    /**
     * @param ViewAnimeDTO $dto
     * @return array
     */
    public function createAnimeCaption(ViewAnimeDTO $dto): array
    {
        $decoded = $this->base62Service->decode($dto->encodedId);

        /** @var Anime|null $anime */
        $anime = $this->animeRepository->findById($decoded);

        if (!$anime) {
            return [];
        }

        return $this->captionService->create(new ViewAnimeCaptionDTO($anime, $dto->chatId));
    }

    /**
     * @param PaginationDTO $dto
     * @return array
     */
    public function createPaginationCaption(PaginationDTO $dto): array
    {
        $animeList = $this->animeRepository->paginate(currentPage: $dto->page);

        return $this->captionService->create(new PaginationCaptionDTO($animeList, $dto->chatId));
    }
}
