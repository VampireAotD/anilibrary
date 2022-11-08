<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Repositories\Traits\CanSearchByName;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository extends BaseRepository implements GenreRepositoryInterface
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
