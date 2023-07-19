<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\DTO\Service\Telegram\Caption\PaginationCaptionDTO;
use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Facades\Telegram\State\UserStateFacade;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Repositories\Filters\PaginationFilter;
use App\Services\Telegram\CaptionService;
use App\Services\Telegram\HashService;

/**
 * Class CaptionUseCase
 * @package App\UseCase
 */
readonly class CaptionUseCase
{
    public function __construct(
        private AnimeRepositoryInterface $animeRepository,
        private HashService              $base62Service,
        private CaptionService           $captionService
    ) {
    }

    public function createDecodedAnimeCaption(ViewEncodedAnimeDTO $dto): array
    {
        $decoded = $this->base62Service->decodeUuid($dto->encodedId);

        /** @var Anime|null $anime */
        $anime = $this->animeRepository->findById($decoded);

        if (!$anime) {
            return [];
        }

        return $this->captionService->create(new ViewAnimeCaptionDTO($anime, $dto->chatId));
    }

    public function createPaginationCaption(PaginationDTO $dto): array
    {
        $animeList = $this->animeRepository->paginate((new PaginationFilter($dto->page)));

        return $this->captionService->create(new PaginationCaptionDTO($animeList, $dto->chatId));
    }

    public function createSearchPaginationCaption(PaginationDTO $dto): array
    {
        $ids = UserStateFacade::getSearchResult($dto->chatId);

        if (!$ids) {
            return [];
        }

        $filter    = new PaginationFilter($dto->page);
        $animeList = $this->animeRepository->paginate($filter->withWhereIn('id', $ids));

        return $this->captionService->create(
            new PaginationCaptionDTO($animeList, $dto->chatId, $dto->page, $dto->queryType)
        );
    }
}
