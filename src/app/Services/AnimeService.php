<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\CreateAnimeDTO;
use App\DTO\Service\Anime\UpdateAnimeDTO;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Traits\CanTransformArray;
use Illuminate\Support\Facades\DB;

/**
 * Class AnimeService
 * @package App\Services
 */
readonly class AnimeService
{
    use CanTransformArray;

    public function __construct(private AnimeRepositoryInterface $animeRepository)
    {
    }

    /**
     * @param CreateAnimeDTO $dto
     * @return Anime
     */
    public function create(CreateAnimeDTO $dto): Anime
    {
        return Anime::query()->updateOrCreate(['title' => $dto->title], $dto->toArray());
    }

    public function update(Anime $anime, UpdateAnimeDTO $dto): Anime
    {
        return DB::transaction(
            function () use ($anime, $dto) {
                $anime->update($dto->toArray());

                if ($dto->urls) {
                    $anime->urls()->upsertRelated($this->toAssociativeArray('url', $dto->urls), 'url');
                }

                if ($dto->synonyms) {
                    $anime->synonyms()->upsertRelated($this->toAssociativeArray('synonym', $dto->synonyms), 'synonym');
                }

                if ($dto->voiceActing) {
                    $anime->voiceActing()->sync($dto->voiceActing);
                }

                if ($dto->genres) {
                    $anime->genres()->sync($dto->genres);
                }

                return $anime;
            }
        );
    }

    public function findByUrl(string $url): ?Anime
    {
        return $this->animeRepository->findByUrl($url);
    }

    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->animeRepository->findByTitleAndSynonyms($data);
    }
}
