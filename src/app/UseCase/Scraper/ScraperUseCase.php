<?php

declare(strict_types=1);

namespace App\UseCase\Scraper;

use App\DTO\Service\Anime\FindSimilarAnimeDTO;
use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\DTO\Service\Scraper\ScrapedDataDTO;
use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Anime;
use App\Rules\Scraper\EncodedImageRule;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\Scraper\Client;
use App\Services\VoiceActingService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class ScraperUseCase
{
    public function __construct(
        private Client             $client,
        private AnimeService       $animeService,
        private VoiceActingService $voiceActingService,
        private GenreService       $genreService
    ) {
    }

    /**
     * @throws RequestException|ValidationException|Throwable
     */
    public function scrapeByUrl(string $url): Anime
    {
        $response = array_merge(['url' => $url], $this->client->scrapeByUrl($url)->json());

        $this->validateResponse($response);

        return $this->upsertAnime(ScrapedDataDTO::fromArray($response));
    }

    /**
     * @throws ValidationException
     */
    private function validateResponse(array $data): void
    {
        Validator::make($data, [
            'title'    => 'required|string',
            'type'     => ['required', new Enum(TypeEnum::class)],
            'status'   => ['required', new Enum(StatusEnum::class)],
            'year'     => 'required|integer',
            'image'    => ['nullable', 'string', new EncodedImageRule()],
            'episodes' => 'nullable|string',
            'rating'   => 'nullable|numeric|gte:0',
        ])->validate();
    }

    /**
     * @throws Throwable
     */
    private function upsertAnime(ScrapedDataDTO $dto): Anime
    {
        $synonyms = array_merge([$dto->title], array_column($dto->synonyms, 'name'));

        $anime = $this->animeService->findSimilar(
            new FindSimilarAnimeDTO(
                $synonyms,
                $dto->type,
                $dto->year
            )
        );

        if ($anime) {
            return $this->animeService->update(
                $anime,
                new UpsertAnimeDTO(
                    $anime->title,
                    $anime->type,
                    (int) $anime->year,
                    [['url' => $dto->url]],
                    $dto->status,
                    $dto->rating,
                    $dto->episodes,
                    synonyms   : $dto->synonyms,
                    voiceActing: $dto->voiceActing,
                    genres     : $dto->genres
                )
            );
        }

        return DB::transaction(function () use ($dto): Anime {
            $genreIds       = [];
            $voiceActingIds = [];

            if ($dto->genres) {
                $genreIds = $this->genreService->sync($dto->genres);
            }

            if ($dto->voiceActing) {
                $voiceActingIds = $this->voiceActingService->sync($dto->voiceActing);
            }

            return $this->animeService->create(
                new UpsertAnimeDTO(
                    $dto->title,
                    $dto->type,
                    $dto->year,
                    [['url' => $dto->url]],
                    $dto->status,
                    $dto->rating,
                    $dto->episodes,
                    $dto->image,
                    $dto->synonyms,
                    $voiceActingIds,
                    $genreIds
                )
            );
        });
    }
}
