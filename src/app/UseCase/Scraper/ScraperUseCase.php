<?php

declare(strict_types=1);

namespace App\UseCase\Scraper;

use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\DTO\Service\Scraper\ScrapedDataDTO;
use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use App\Models\AnimeUrl;
use App\Rules\Scraper\EncodedImageRule;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\ImageService;
use App\Services\Scraper\ScraperService;
use App\Services\VoiceActingService;
use App\Traits\CanTransformArray;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class ScraperUseCase
{
    use CanTransformArray;

    public function __construct(
        private ScraperService     $scraperService,
        private AnimeService       $animeService,
        private ImageService       $imageService,
        private VoiceActingService $voiceActingService,
        private GenreService       $genreService
    ) {
    }

    /**
     * @throws RequestException|ValidationException|Throwable
     */
    public function scrapeAndCreateAnime(string $url): Anime
    {
        $response = $this->scraperService->sendScrapeRequest($url)->json();
        $response = array_merge(['url' => $url], $response);

        Validator::make(
            $response,
            [
                'title'  => 'required|string',
                'image'  => ['nullable', 'string', new EncodedImageRule()],
                'status' => ['required', new Enum(AnimeStatusEnum::class)],
            ]
        )->validate();

        return $this->upsert(new ScrapedDataDTO(...$response));
    }

    private function upsert(ScrapedDataDTO $dto): Anime
    {
        $anime = $this->animeService->findByTitleAndSynonyms(array_merge($dto->synonyms, [$dto->title]));

        // Some anime have similar synonyms, this prevents updating wrong anime
        // TODO try to resolve this in better way
        $domain = parse_url($dto->url)['host'] ?? '';

        if (
            $anime
            && $anime->urls->filter(fn(AnimeUrl $animeUrl) => str_contains($animeUrl->url, $domain))->isEmpty()
        ) {
            $anime->urls()->updateOrCreate(['url' => $dto->url], [$dto->url]);
            $anime->synonyms()->upsertRelated($dto->synonyms, ['name']);

            return $anime;
        }

        return DB::transaction(function () use ($dto): Anime {
            $anime = $this->animeService->create(
                new UpsertAnimeDTO(
                    $dto->title,
                    AnimeStatusEnum::from($dto->status),
                    $dto->rating,
                    $dto->episodes
                )
            );

            $anime->urls()->updateOrCreate(['url' => $dto->url], [$dto->url]);

            // Don't update image on CDN if there is one already
            if ($dto->image && !$anime->image) {
                $this->imageService->upsert($dto->image, $anime);
            }

            if ($dto->synonyms) {
                $anime->synonyms()->upsertRelated($dto->synonyms, ['name']);
            };

            if ($dto->voiceActing) {
                $anime->voiceActing()->syncWithoutDetaching($this->voiceActingService->sync($dto->voiceActing));
            }

            if ($dto->genres) {
                $anime->genres()->syncWithoutDetaching($this->genreService->sync($dto->genres));
            }

            return $anime;
        });
    }
}
