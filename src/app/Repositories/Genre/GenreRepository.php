<?php

declare(strict_types=1);

namespace App\Repositories\Genre;

use App\Filters\QueryFilterInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository implements GenreRepositoryInterface
{
    protected Builder | Genre $query;

    public function __construct()
    {
        $this->query = Genre::query();
    }

    /**
     * @param array<QueryFilterInterface> $filters
     */
    public function withFilters(array $filters): static
    {
        $this->query = Genre::filter($filters);

        return $this;
    }

    /**
     * @return LazyCollection<int, Genre>
     */
    public function getAll(): LazyCollection
    {
        return $this->query->lazy();
    }

    /**
     * @param array<string> $names
     * @return Collection<int, Genre>
     */
    public function findByNames(array $names): Collection
    {
        return $this->query->whereIn('name', $names)->get();
    }

    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, array | string $uniqueBy): int
    {
        return $this->query->upsert($data, $uniqueBy);
    }
}
