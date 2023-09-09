<?php

declare(strict_types=1);

namespace App\Repositories\Genre;

use App\Models\Genre;
use App\Repositories\Traits\CanSearchByName;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GenreRepository
 * @package App\Repositories
 */
class GenreRepository implements GenreRepositoryInterface
{
    use CanSearchByName;

    protected Builder | Genre $query;

    public function __construct()
    {
        $this->query = Genre::query();
    }

    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, array | string $uniqueBy): int
    {
        return $this->query->upsert($data, $uniqueBy);
    }
}
