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
    private Model $model;

    public function __construct()
    {
        $this->model = app($this->resolveModel());
    }

    /**
     * @return string
     */
    abstract protected function resolveModel(): string;

    /**
     * @return Model
     */
    public function query(): Model
    {
        return clone $this->model;
    }

    /**
     * @param string $id
     * @return Model|null
     */
    public function findById(string $id): ?Model
    {
        return $this->query()->find($id);
    }
}
