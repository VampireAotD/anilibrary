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
     * @param array    $similarNames
     * @param string[] $columns
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames, array $columns = ['*']): Collection
    {
        return $this->model()->select($columns)->whereIn('name', $similarNames)->get();
    }

    /**
     * @param string   $name
     * @param string[] $columns
     * @return Model|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Model
    {
        return $this->model()->select($columns)->where('name', $name)->first();
    }
}
