<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use App\Repositories\BaseRepository;
use App\Repositories\Filters\PaginationFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

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

    public function create(array $data): Anime
    {
        return $this->model()->updateOrCreate(['title' => $data['title']], $data);
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

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleased(): LazyCollection
    {
        return $this->model()->with('urls')->whereNot('status', AnimeStatusEnum::READY->value)->lazy();
    }
}
