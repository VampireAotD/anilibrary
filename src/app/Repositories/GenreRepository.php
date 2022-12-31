<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Repositories\Traits\CanSearchByName;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository extends BaseRepository implements GenreRepositoryInterface
{
    use CanSearchByName;

    /**
     * @return Builder|Genre
     */
    protected function model(): Builder | Genre
    {
        return Genre::query();
    }

    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, array | string $uniqueBy): int
    {
        return $this->model()->upsert($data, $uniqueBy);
    }
}
