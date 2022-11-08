<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository
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
}
