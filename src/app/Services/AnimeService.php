<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\Filters\QueryFilterInterface;
use App\Filters\RelationFilter;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use App\Traits\CanTransformArray;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

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

    public function findById(string $id): ?Anime
    {
        return $this->animeRepository->findById($id);
    }

    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->animeRepository->findByTitleAndSynonyms($data);
    }

    public function findByUrl(string $url): ?Anime
    {
        return $this->animeRepository->findByUrl($url);
    }

    public function randomAnime(): ?Anime
    {
        return $this->animeRepository->findRandomAnime();
    }

    /**
     * @param array<QueryFilterInterface> $filters
     * @return Collection|LazyCollection<int, Anime>
     */
    public function all(array $filters = []): Collection | LazyCollection
    {
        return $this->animeRepository->withFilters($filters)->getAll();
    }

    public function paginate(AnimePaginationDTO $dto): LengthAwarePaginator
    {
        return $this->animeRepository->withFilters($dto->filters)->paginate($dto->page, $dto->perPage);
    }

    public function unreleased(): LazyCollection
    {
        return $this->animeRepository->getUnreleased();
    }

    public function getParsedAnimePerMonth(): array
    {
        // Initial array of parsed anime per month where month is a key, and value is a parsed anime count
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

    private function upsertRelations(Anime $anime, UpsertAnimeDTO $dto): void
    {
        if ($dto->urls) {
            $anime->urls()->upsertRelated($dto->urls, 'url');
        }

        if ($dto->synonyms) {
            $anime->synonyms()->upsertRelated($dto->synonyms, 'name');
        }

        if ($dto->voiceActing) {
            $anime->voiceActing()->sync($dto->voiceActing);
        }

        if ($dto->genres) {
            $anime->genres()->sync($dto->genres);
        }
    }
}
