<?php

namespace App\Repositories;

use App\Models\Genre;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository extends BaseRepository
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Genre::class;
    }
}
