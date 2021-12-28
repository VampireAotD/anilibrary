<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
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
     * @param string $uuid
     * @return Model|null
     */
    public function findById(string $uuid): ?Model
    {
        return $this->query()->find($uuid);
    }

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
