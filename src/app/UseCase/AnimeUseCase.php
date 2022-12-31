<?php

declare(strict_types=1);

namespace App\UseCase;

use App\DTO\Service\Anime\CreateDTO;
use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Models\Anime;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Services\AnimeService;
use App\Services\AnimeSynonymService;
use App\Services\GenreService;
use App\Services\ImageService;
use App\Services\VoiceActingService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Class AnimeUseCase
 * @package App\UseCase
 */
class AnimeUseCase
{
    public function __construct(
        private readonly AnimeService           $animeService,
        private readonly AnimeSynonymService    $animeSynonymService,
        private readonly ImageService           $imageService,
        private readonly VoiceActingService     $voiceActingService,
        private readonly GenreService           $genreService,
        private readonly TagRepositoryInterface $tagRepository
    ) {
    }

    /**
     * @param string   $url
     * @param int|null $chatId
     * @return ScrapedDataDTO
     * @throws RequestException
     */
    public function sendScrapeRequest(string $url, ?int $chatId = null): ScrapedDataDTO
    {
        $response = Http::post(sprintf('%s/api/v1/anime/parse', config('scraper.url')), ['url' => $url])->throw();

        $data = array_merge(['url' => $url, 'telegramId' => $chatId], $response->json());

        return new ScrapedDataDTO(...$data);
    }

    /**
     * @param string $url
     * @return Anime|null
     */
    public function findByUrl(string $url): ?Anime
    {
        return $this->animeService->findByUrl($url);
    }

    /**
     * @param ScrapedDataDTO $dto
     * @return Anime
     * @throws InvalidScrapedDataException|Throwable
     */
    public function createAnime(ScrapedDataDTO $dto): Anime
    {
        if (!$dto->validate()) {
            throw new InvalidScrapedDataException();
        }

        $anime = $this->animeService->findByTitleAndSynonyms(array_merge($dto->synonyms, [$dto->title]));

        if ($anime) {
            $anime->synonyms()->upsertRelated(
                $this->animeSynonymService->mapIntoSynonymsArray($dto->synonyms),
                ['synonym']
            );
            $anime->urls()->updateOrCreate(['url' => $dto->url], [$dto->url]);

            return $anime;
        }

        return DB::transaction(
            function () use ($dto): Anime {
                $anime = $this->animeService->create(
                    new CreateDTO($dto->title, $dto->status, $dto->rating, $dto->episodes)
                );

                $anime->urls()->updateOrCreate(['url' => $dto->url], [$dto->url]);

                if ($dto->synonyms) {
                    $anime->synonyms()->upsertRelated(
                        $this->animeSynonymService->mapIntoSynonymsArray($dto->synonyms),
                        ['synonym']
                    );
                }

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
        );
    }
}
