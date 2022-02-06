<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Traits\CanSearchByName;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository extends BaseRepository
{
    use CanSearchByName;

    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Genre::class;
    }
}
