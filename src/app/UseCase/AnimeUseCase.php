<?php

declare(strict_types=1);

namespace App\UseCase;

use App\DTO\Service\Anime\CreateDTO;
use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Models\Anime;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\ImageService;
use App\Services\VoiceActingService;

/**
 * Class AnimeUseCase
 * @package App\UseCase
 */
class AnimeUseCase
{
    public function __construct(
        private readonly AnimeService           $animeService,
        private readonly ImageService           $imageService,
        private readonly VoiceActingService     $voiceActingService,
        private readonly GenreService           $genreService,
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    /**
     * @param ScrapedDataDTO $dto
     * @return Anime
     * @throws InvalidScrapedDataException
     */
    public function createAnime(ScrapedDataDTO $dto): Anime
    {
        if (!$dto->validate()) {
            throw new InvalidScrapedDataException();
        }

        $anime = $this->animeService->create(
            new CreateDTO($dto->url, $dto->title, $dto->status, $dto->rating, $dto->episodes)
        );

        if ($dto->getImage()) {
            $this->imageService->upsert($dto->getImage(), $anime);
        }

        if ($dto->voiceActing) {
            $anime->voiceActing()->sync($this->voiceActingService->sync($dto->voiceActing), false);
        }

        if ($dto->genres) {
            $anime->genres()->sync($this->genreService->sync($dto->genres), false);
        }

        if ($dto->getTelegramId()) {
            $anime->tags()->sync($this->tagRepository->findByTelegramId($dto->getTelegramId()), false);
        }

        return $anime;
    }
}
