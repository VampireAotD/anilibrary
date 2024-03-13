<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Enums\AnimeStatusEnum;
use App\Filters\QueryFilterInterface;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

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

    public function findById(string $id): ?Anime
    {
        return $this->query->find($id);
    }

    /**
     * @param array<string> $data
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
        /** @psalm-suppress InvalidArgument */
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

    /**
     * @return Collection<int, Anime>
     */
    public function getAll(): Collection
    {
        return $this->query->get();
    }

    public function paginate(int $page, int $perPage = 1): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, page: $page);
    }

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleased(): LazyCollection
    {
        return $this->query->with('urls')->whereNot('status', AnimeStatusEnum::READY->value)->lazy();
    }

    /**
     * @param int $limit
     * @return Collection<int, Anime>
     */
    public function getLatestAnime(int $limit = 10): Collection
    {
        return $this->query->limit($limit)->latest()->get();
    }

    /**
     * @return array<int, int>
     */
    public function getAddedAnimePerMonth(): array
    {
        return $this->query->selectRaw('COUNT(id) as per_month, MONTH(created_at) as month_number')
                           ->groupBy('month_number')
                           ->pluck('per_month', 'month_number')
                           ->toArray();
    }

    public function count(): int
    {
        return $this->query->count('id');
    }
}
