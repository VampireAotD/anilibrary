<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Telegram\Anime\AnimeMessageDTO;
use App\DTO\UseCase\Telegram\Anime\GenerateAnimeListDTO;
use App\DTO\UseCase\Telegram\Anime\GenerateAnimeMessageDTO;
use App\DTO\UseCase\Telegram\Anime\GenerateAnimeSearchResultDTO;
use App\Enums\Telegram\Callbacks\CallbackDataTypeEnum;
use App\Exceptions\UseCase\Telegram\AnimeMessageException;
use App\Facades\Telegram\State\UserStateFacade;
use App\Filters\WhereInFilter;
use App\Services\Anime\AnimeService;
use App\Services\Telegram\AnimeMessageService;
use App\Services\Telegram\EncoderService;

final readonly class AnimeMessageUseCase
{
    public function __construct(
        private AnimeService        $animeService,
        private AnimeMessageService $animeMessageService,
        private EncoderService      $encoderService
    ) {
    }

    /**
     * @throws AnimeMessageException
     */
    public function generateAnimeMessage(GenerateAnimeMessageDTO $dto): AnimeMessageDTO
    {
        $id = $dto->animeId;

        if ($dto->idEncoded) {
            $id = $this->encoderService->decodeId($dto->animeId);
        }

        $anime = $this->animeService->findById($id);

        if (!$anime) {
            throw AnimeMessageException::animeNotFound($dto->animeId);
        }

        return $this->animeMessageService->createMessage($anime);
    }

    /**
     * @throws AnimeMessageException
     */
    public function generateAnimeList(GenerateAnimeListDTO $dto): AnimeMessageDTO
    {
        $animeList = $this->animeService->paginate(new AnimePaginationDTO($dto->page));

        if ($animeList->isEmpty()) {
            throw AnimeMessageException::couldNotGetDataForPage($dto->page);
        }

        return $this->animeMessageService->createMessageWithPagination(
            $animeList,
            CallbackDataTypeEnum::ANIME_LIST
        );
    }

    /**
     * @throws AnimeMessageException
     */
    public function generateSearchResult(GenerateAnimeSearchResultDTO $dto): AnimeMessageDTO
    {
        $ids = UserStateFacade::getSearchResult($dto->userId);

        if (!$ids) {
            throw AnimeMessageException::noSearchResultsAvailable();
        }

        $animeList = $this->animeService->paginate(
            new AnimePaginationDTO(page: $dto->page, filters: [new WhereInFilter('id', $ids)])
        );

        return $this->animeMessageService->createMessageWithPagination(
            $animeList,
            CallbackDataTypeEnum::SEARCH_LIST
        );
    }
}
