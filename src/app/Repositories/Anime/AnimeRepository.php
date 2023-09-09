<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use App\Repositories\Filters\QueryFilterInterface;
use App\Repositories\Params\PaginationParams;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

/**
 * Class AnimeRepository
 * @package App\Repositories
 */
class AnimeRepository implements AnimeRepositoryInterface
{
    /**
     * @var Builder|Anime
     */
    protected Builder | Anime $query;

    public function __construct()
    {
        $this->query = Anime::query();
    }

    /**
     * @param array<QueryFilterInterface> $filters
     */
    public function withFilters(array $filters): static
    {
        $this->query = Anime::filter($filters);

        return $this;
    }

    public function create(array $data): Anime
    {
        return $this->query->updateOrCreate(['title' => $data['title']], $data);
    }

    /**
     * @param string $id
     * @return Anime|null
     */
    public function findById(string $id): ?Anime
    {
        return $this->query->find($id);
    }

    /**
     * @param array<string> $data
     * @return Anime|null
     */
    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->query
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
        return $this->query->withWhereHas(
            'urls',
            fn(Builder | HasMany $query) => $query->where('url', $url)
        )->first();
    }

    /**
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime
    {
        return $this->query->inRandomOrder()->limit(1)->first();
    }

    public function getAll(): Collection
    {
        return $this->query->get();
    }

    public function paginate(PaginationParams $filter): LengthAwarePaginator
    {
        return $this->query->paginate($filter->perPage, page: $filter->page);
    }

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleased(): LazyCollection
    {
        return $this->query->with('urls')->whereNot('status', AnimeStatusEnum::READY->value)->lazy();
    }
}
