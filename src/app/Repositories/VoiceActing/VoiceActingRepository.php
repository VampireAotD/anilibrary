<?php

declare(strict_types=1);

namespace App\Repositories\VoiceActing;

use App\Filters\QueryFilterInterface;
use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

class VoiceActingRepository implements VoiceActingRepositoryInterface
{
    /**
     * @var Builder|VoiceActing
     */
    protected Builder | VoiceActing $query;

    public function __construct()
    {
        $this->query = VoiceActing::query();
    }

    /**
     * @param array<QueryFilterInterface> $filters
     */
    public function withFilters(array $filters): static
    {
        $this->query = VoiceActing::filter($filters);

        return $this;
    }

    /**
     * @return LazyCollection<int, VoiceActing>
     */
    public function getAll(): LazyCollection
    {
        return $this->query->lazy();
    }

    /**
     * @param array<string> $names
     * @return Collection<int, VoiceActing>
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
