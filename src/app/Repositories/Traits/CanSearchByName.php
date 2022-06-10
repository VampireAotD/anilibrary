<?php

declare(strict_types=1);

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait CanSearchByName
{
    /**
     * @param array $similarNames
     * @param array|string[] $columns
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames, array $columns = ['*']): Collection
    {
        return $this->query()->select($columns)->whereIn('name', $similarNames)->get();
    }

    /**
     * @param string $name
     * @param array $columns
     * @return Model|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Model
    {
        return $this->query()->select($columns)->where('name', $name)->first();
    }
}
