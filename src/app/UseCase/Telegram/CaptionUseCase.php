<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\DTO\Service\Telegram\Caption\PaginationCaptionDTO;
use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\Base62Service;
use App\Services\Telegram\CaptionService;

/**
 * Class CaptionUseCase
 * @package App\UseCase
 */
readonly class CaptionUseCase
{
    public function __construct(
        private AnimeRepositoryInterface $animeRepository,
        private Base62Service            $base62Service,
        private CaptionService           $captionService
    ) {
    }

    public function createDecodedAnimeCaption(ViewEncodedAnimeDTO $dto): array
    {
        $decoded = $this->base62Service->decode($dto->encodedId);

        /** @var Anime|null $anime */
        $anime = $this->animeRepository->findById($decoded);

        if (!$anime) {
            return [];
        }

        return $this->captionService->create(new ViewAnimeCaptionDTO($anime, $dto->chatId));
    }

    public function createPaginationCaption(PaginationDTO $dto): array
    {
        $animeList = $this->animeRepository->paginate(currentPage: $dto->page);

        return $this->captionService->create(new PaginationCaptionDTO($animeList, $dto->chatId));
    }
}
