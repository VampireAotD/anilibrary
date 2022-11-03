<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\Common\FindById;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository implements FindById
{
    /**
     * @return string
     */
    abstract protected function resolveModel(): string;

    /**
     * @return Model
     */
    public function model(): Model
    {
        return app($this->resolveModel());
    }

    /**
     * @param string $id
     * @return Model|null
     */
    public function findById(string $id): ?Model
    {
        return $this->model()->find($id);
    }
}
