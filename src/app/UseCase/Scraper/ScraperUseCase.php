<?php

declare(strict_types=1);

namespace App\UseCase\Scraper;

use App\DTO\Service\Anime\CreateAnimeDTO;
use App\DTO\Service\Anime\FindSimilarAnimeDTO;
use App\DTO\Service\Anime\UpdateAnimeDTO;
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
     * @param array<string, mixed> $data
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

        if (!is_null($anime)) {
            return DB::transaction(function () use ($anime, $dto): Anime {
                [$genres, $voiceActing] = $this->syncVoiceActingAndGenres($dto);

                $updateDto = new UpdateAnimeDTO(
                    status     : $dto->status,
                    episodes   : $dto->episodes,
                    urls       : [['url' => $dto->url]],
                    synonyms   : $dto->synonyms,
                    voiceActing: $voiceActing,
                    genres     : $genres
                );

                return $this->animeService->update($anime, $updateDto);
            });
        }

        return DB::transaction(function () use ($dto): Anime {
            [$genres, $voiceActing] = $this->syncVoiceActingAndGenres($dto);

            $createDto = new CreateAnimeDTO(
                title      : $dto->title,
                year       : $dto->year,
                urls       : [['url' => $dto->url]],
                type       : $dto->type,
                status     : $dto->status,
                rating     : $dto->rating,
                episodes   : $dto->episodes,
                image      : $dto->image,
                synonyms   : $dto->synonyms,
                voiceActing: $voiceActing,
                genres     : $genres
            );

            return $this->animeService->create($createDto);
        });
    }

    /**
     * @return list<list<string>>
     */
    private function syncVoiceActingAndGenres(ScrapedDataDTO $dto): array
    {
        return [
            $this->genreService->sync($dto->genres),
            $this->voiceActingService->sync($dto->voiceActing),
        ];
    }
}
