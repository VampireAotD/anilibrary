<?php

declare(strict_types=1);

namespace App\UseCase;

use App\DTO\Service\Telegram\CreateAnimeCaptionDTO;
use App\DTO\UseCase\CallbackQuery\AddedAnimeDTO;
use App\DTO\UseCase\CallbackQuery\PaginationDTO;
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
     * @param AddedAnimeDTO $dto
     * @return array
     */
    public function addedAnimeCaption(AddedAnimeDTO $dto): array
    {
        $decoded = $this->base62Service->decode($dto->encodedId);
        /** @var Anime|null $anime */
        $anime = $this->animeRepository->findById($decoded);

        if (!$anime) {
            return [];
        }

        return $this->captionService->create(new CreateAnimeCaptionDTO($anime, $dto->chatId));
    }

    /**
     * @param PaginationDTO $dto
     * @return array
     */
    public function paginate(PaginationDTO $dto): array
    {
        $animeList = $this->animeRepository->paginate(currentPage: $dto->page);

        return $this->captionService->create(new CreateAnimeCaptionDTO($animeList->first(), $dto->chatId, $animeList));
    }
}
