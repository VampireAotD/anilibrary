<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Collection;

trait CanSearchBySimilarNames
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
}
