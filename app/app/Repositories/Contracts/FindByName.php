<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface FindByName
 * @package App\Repositories\Contracts
 */
interface FindByName
{
    /**
     * @param string $name
     * @param array $columns
     * @return Model|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Model;
}
