<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface FindById
{
    /**
     * @param string $id
     * @return Model|null
     */
    public function findById(string $id): ?Model;
}
