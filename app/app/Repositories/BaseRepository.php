<?php

namespace App\Repositories;

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
    protected abstract function resolveModel(): string;

    /**
     * @return Model
     */
    public function query(): Model
    {
        return clone $this->model;
    }
}
