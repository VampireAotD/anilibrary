<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Traits\CanSearchBySimilarNames;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository extends BaseRepository
{
    use CanSearchBySimilarNames;

    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Genre::class;
    }
}
