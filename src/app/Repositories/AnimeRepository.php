<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Repositories\Filters\PaginationFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class AnimeRepository
 * @package App\Repositories
 */
class AnimeRepository extends BaseRepository implements AnimeRepositoryInterface
{
    /**
     * @return Builder|Anime
     */
    protected function model(): Builder | Anime
    {
        return Anime::query();
    }

    /**
     * @param string $id
     * @return Anime|null
     */
    public function findById(string $id): ?Anime
    {
        return $this->model()->find($id);
    }

    /**
     * @param array<string> $data
     * @return Anime|null
     */
    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->model()
                    ->whereIn('title', $data)
                    ->with('synonyms')
                    ->orWhereHas('synonyms', fn(Builder $query) => $query->whereIn('synonym', $data))
                    ->first();
    }

    /**
     * @param string $url
     * @return Anime|null
     */
    public function findByUrl(string $url): ?Anime
    {
        /** @phpstan-ignore-next-line */
        return $this->model()->withWhereHas(
            'urls',
            fn(Builder | HasMany $query) => $query->where('url', $url)
        )->first();
    }

    /**
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime
    {
        return $this->model()->inRandomOrder()->limit(1)->first();
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getAll(
        array $columns = ['*'],
        array $relations = ['image', 'genres', 'voiceActing', 'urls', 'synonyms']
    ): Collection {
        return $this->model()->select($columns)->with($relations)->get();
    }

    public function paginate(PaginationFilter $filter): LengthAwarePaginator
    {
        return $filter->apply($this->model())->paginate($filter->perPage, page: $filter->page);
    }
}
