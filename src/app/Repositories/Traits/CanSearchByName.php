<?php

declare(strict_types=1);

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait CanSearchByName
 * @package App\Repositories\Traits
 */
trait CanSearchByName
{
    /**
     * @param array<string> $similarNames
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames): Collection
    {
        return $this->query->whereIn('name', $similarNames)->get();
    }

    /**
     * @param string $name
     * @return Model|null
     */
    public function findByName(string $name): ?Model
    {
        return $this->query->where('name', $name)->first();
    }
}
