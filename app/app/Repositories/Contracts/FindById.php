<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface FindById
 * @package App\Repositories\Contracts
 */
interface FindById
{
    /**
     * @param string $uuid
     * @return Model|null
     */
    public function findById(string $uuid): ?Model;
}
