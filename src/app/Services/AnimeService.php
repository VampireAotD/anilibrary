<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Repositories\Filters\RelationFilter;
use App\Traits\CanTransformArray;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class AnimeService
 * @package App\Services
 */
final readonly class AnimeService
{
    use CanTransformArray;

    public function __construct(private AnimeRepositoryInterface $animeRepository)
    {
    }

    public function create(UpsertAnimeDTO $dto): Anime
    {
        return DB::transaction(function () use ($dto) {
            $anime = $this->animeRepository->create($dto->toArray());
            $this->upsertRelations($anime, $dto);

            return $anime;
        });
    }

    public function update(Anime $anime, UpsertAnimeDTO $dto): Anime
    {
        return DB::transaction(function () use ($anime, $dto) {
            $anime->update($dto->toArray());
            $this->upsertRelations($anime, $dto);

            return $anime;
        });
    }

    public function findByUrl(string $url): ?Anime
    {
        return $this->animeRepository->findByUrl($url);
    }

    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->animeRepository->findByTitleAndSynonyms($data);
    }

    private function upsertRelations(Anime $anime, UpsertAnimeDTO $dto): void
    {
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
    }

    public function getParsedAnimePerMonth(): array
    {
        $initial = array_fill(0, 12, 0);

        $perMonth = $this->animeRepository->getAddedAnimePerMonth();

        return array_replace($initial, $perMonth);
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getTenLatestAnime(): Collection
    {
        return $this->animeRepository->withFilters([new RelationFilter(['image:model_id,path'])])->getLatestAnime();
    }

    public function countAnime(): int
    {
        return $this->animeRepository->count();
    }
}
