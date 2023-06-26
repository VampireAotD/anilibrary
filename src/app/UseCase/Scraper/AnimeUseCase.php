<?php

declare(strict_types=1);

namespace App\UseCase\Scraper;

use App\DTO\Service\Anime\CreateAnimeDTO;
use App\DTO\Service\Scraper\ScrapedDataDTO;
use App\Models\Anime;
use App\Models\AnimeUrl;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Rules\Scraper\EncodedImageRule;
use App\Services\AnimeService;
use App\Services\AnimeSynonymService;
use App\Services\GenreService;
use App\Services\ImageService;
use App\Services\Scraper\RequestService;
use App\Services\VoiceActingService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Class AnimeUseCase
 * @package App\UseCase\Scraper
 */
final readonly class AnimeUseCase
{
    public function __construct(
        private RequestService         $requestService,
        private AnimeService           $animeService,
        private AnimeSynonymService    $animeSynonymService,
        private ImageService           $imageService,
        private VoiceActingService     $voiceActingService,
        private GenreService           $genreService,
        private TagRepositoryInterface $tagRepository
    ) {
    }

    /**
     * @throws RequestException|ValidationException|Throwable
     */
    public function scrapeAndCreateAnime(string $url, ?int $telegramId = null): Anime
    {
        $response = $this->requestService->sendScrapeRequest($url)->json();
        $response = array_merge(['url' => $url, 'telegramId' => $telegramId], $response);

        Validator::make(
            $response,
            ['title' => 'required|string', 'image' => ['nullable', 'string', new EncodedImageRule()]]
        )->validate();

        return $this->createAnime(new ScrapedDataDTO(...$response));
    }

    private function createAnime(ScrapedDataDTO $dto): Anime
    {
        $anime = $this->animeService->findByTitleAndSynonyms(array_merge($dto->synonyms, [$dto->title]));

        // Some anime have similar synonyms, this prevents updating wrong anime
        // TODO try to resolve this in better way
        $parsed = parse_url($dto->url);

        if (
            $anime
            && $anime->urls->filter(fn(AnimeUrl $animeUrl) => str_contains($animeUrl->url, $parsed['host']))->isEmpty()
        ) {
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
                    new CreateAnimeDTO($dto->title, $dto->status, $dto->rating, $dto->episodes)
                );

                $anime->urls()->updateOrCreate(['url' => $dto->url], [$dto->url]);

                if ($dto->synonyms) {
                    $anime->synonyms()->upsertRelated(
                        $this->animeSynonymService->mapIntoSynonymsArray($dto->synonyms),
                        ['synonym']
                    );
                }

                if ($dto->image) {
                    $this->imageService->upsert($dto->image, $anime);
                }

                if ($dto->voiceActing) {
                    $anime->voiceActing()->sync($this->voiceActingService->sync($dto->voiceActing), false);
                }

                if ($dto->genres) {
                    $anime->genres()->sync($this->genreService->sync($dto->genres), false);
                }

                if ($dto->telegramId) {
                    $anime->tags()->sync($this->tagRepository->findByTelegramId($dto->telegramId), false);
                }

                return $anime;
            }
        );
    }
}
