<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Anime\FindSimilarAnimeDTO;
use App\DTO\Service\Anime\UpsertAnimeDTO;
use App\Filters\QueryFilterInterface;
use App\Filters\RelationFilter;
use App\Jobs\Image\UploadJob;
use App\Models\Anime;
use App\Repositories\Anime\AnimeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Throwable;

final readonly class AnimeService
{
    public function __construct(private AnimeRepositoryInterface $animeRepository)
    {
    }

    /**
     * @throws Throwable
     */
    public function create(UpsertAnimeDTO $dto): Anime
    {
        return DB::transaction(function () use ($dto) {
            $anime = $this->animeRepository->create($dto->toArray());
            $this->upsertRelations($anime, $dto);

            return $anime;
        });
    }

    /**
     * @throws Throwable
     */
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

    public function findSimilar(FindSimilarAnimeDTO $dto): ?Anime
    {
        return $this->animeRepository->findSimilar($dto->toArray());
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
        // Initial array of parsed anime per month, where month is a key, and value is a parsed anime count
        $initial = array_fill(0, 12, 0);

        $perMonth = $this->animeRepository->getAddedAnimePerMonth();

        return array_replace($initial, $perMonth);
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getTenLatestAnime(): Collection
    {
        return $this->animeRepository->withFilters([new RelationFilter(['image:id,path'])])->getLatestAnime();
    }

    public function countAnime(): int
    {
        return $this->animeRepository->count();
    }

    private function upsertRelations(Anime $anime, UpsertAnimeDTO $dto): void
    {
        $anime->urls()->upsertRelated($dto->urls, ['url']);

        // Anime will always have default image path related to it
        // even if there is no actual record in DB, that's because
        // of Laravel withDefault method on image relation, so here
        // need to check if image has default path then it can be
        // replaced with new one
        if ($dto->image && $anime->image->is_default) {
            UploadJob::dispatch($anime, $dto->image);
        }

        if ($dto->synonyms) {
            $anime->synonyms()->upsertRelated($dto->synonyms, ['name']);
        }

        if ($dto->genres) {
            $anime->genres()->syncWithoutDetaching($dto->genres);
        }

        if ($dto->voiceActing) {
            $anime->voiceActing()->syncWithoutDetaching($dto->voiceActing);
        }
    }
}
