<?php

declare(strict_types=1);

namespace App\UseCase\Scraper;

use App\DTO\Service\Anime\AnimeDTO;
use App\DTO\Service\Anime\FindSimilarAnimeDTO;
use App\DTO\Service\Scraper\ScrapedDataDTO;
use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use App\Models\Anime;
use App\Rules\Scraper\EncodedImageRule;
use App\Services\Anime\AnimeService;
use App\Services\Genre\GenreService;
use App\Services\Scraper\Client;
use App\Services\VoiceActing\VoiceActingService;
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
            'rating'   => 'required|numeric|gte:0',
            'episodes' => 'required|integer',
            'year'     => 'required|integer',
            'image'    => ['nullable', 'string', new EncodedImageRule()],
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
                new AnimeDTO(
                    $anime->title,
                    $anime->type, // @phpstan-ignore-line Ignored because of parser issues
                    $dto->status,
                    $dto->rating,
                    $dto->episodes,
                    (int) $anime->year,
                    [['url' => $dto->url]],
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
                new AnimeDTO(
                    $dto->title,
                    $dto->type,
                    $dto->status,
                    $dto->rating,
                    $dto->episodes,
                    $dto->year,
                    [['url' => $dto->url]],
                    $dto->image,
                    $dto->synonyms,
                    $voiceActingIds,
                    $genreIds
                )
            );
        });
    }
}
