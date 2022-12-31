<?php

namespace App\Repositories\Contracts\Common;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface FindByName
 * @package App\Repositories\Contracts
 */
interface FindByName
{
    /**
     * @param string   $name
     * @param string[] $columns
     * @return Model|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Model;

    /**
     * @param string[] $similarNames
     * @param string[] $columns
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames, array $columns = ['*']): Collection;
}
