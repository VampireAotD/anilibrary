<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Telegram\Caption\PaginationCaptionDTO;
use App\DTO\Service\Telegram\Caption\ViewAnimeCaptionDTO;
use App\DTO\UseCase\Telegram\Caption\PaginationDTO;
use App\DTO\UseCase\Telegram\Caption\ViewEncodedAnimeDTO;
use App\Facades\Telegram\State\UserStateFacade;
use App\Filters\InFilter;
use App\Models\Anime;
use App\Services\AnimeService;
use App\Services\Telegram\CaptionService;
use App\Services\Telegram\IdCodecService;

/**
 * Class CaptionUseCase
 * @package App\UseCase
 */
readonly class CaptionUseCase
{
    public function __construct(
        private AnimeService   $animeService,
        private IdCodecService $idCodecService,
        private CaptionService $captionService
    ) {
    }

    public function createDecodedAnimeCaption(ViewEncodedAnimeDTO $dto): array
    {
        $decoded = $this->idCodecService->decode($dto->encodedId);

        /** @var Anime|null $anime */
        $anime = $this->animeService->findById($decoded);

        if (!$anime) {
            return [];
        }

        return $this->captionService->create(new ViewAnimeCaptionDTO($anime, $dto->chatId));
    }

    public function createPaginationCaption(PaginationDTO $dto): array
    {
        $animeList = $this->animeService->paginate(new AnimePaginationDTO($dto->page));

        return $this->captionService->create(new PaginationCaptionDTO($animeList, $dto->chatId));
    }

    public function createSearchPaginationCaption(PaginationDTO $dto): array
    {
        $ids = UserStateFacade::getSearchResult($dto->chatId);

        if (!$ids) {
            return [];
        }

        $animeList = $this->animeService->paginate(
            new AnimePaginationDTO($dto->page, filters: [new InFilter('id', $ids)])
        );

        return $this->captionService->create(
            new PaginationCaptionDTO($animeList, $dto->chatId, $dto->page, $dto->queryType)
        );
    }
}
