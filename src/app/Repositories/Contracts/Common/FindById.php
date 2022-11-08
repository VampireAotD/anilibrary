<?php

namespace App\Repositories\Contracts\Common;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface FindById
 * @package App\Repositories\Contracts
 */
interface FindById
{
    /**
     * @param string $id
     * @return Model|null
     */
    public function findById(string $id): ?Model;
}
