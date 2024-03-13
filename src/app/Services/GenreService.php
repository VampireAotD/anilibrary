<?php

declare(strict_types=1);

namespace App\Services;

use App\Filters\QueryFilterInterface;
use App\Models\Genre;
use App\Repositories\Genre\GenreRepositoryInterface;
use App\Traits\CanTransformArray;
use Illuminate\Support\LazyCollection;

final readonly class GenreService
{
    use CanTransformArray;

    public function __construct(private GenreRepositoryInterface $genreRepository)
    {
    }

    /**
     * @param array<QueryFilterInterface> $filters
     * @return LazyCollection<int, Genre>
     */
    public function all(array $filters = []): LazyCollection
    {
        return $this->genreRepository->withFilters($filters)->getAll();
    }

    /**
     * @param string[] $genres
     * @return string[]
     */
    public function sync(array $genres): array
    {
        $stored    = $this->genreRepository->findByNames($genres);
        $newGenres = array_diff($genres, $stored->pluck('name')->toArray());

        if (!$newGenres) {
            return $stored->pluck('id')->toArray();
        }

        $newGenres = $this->toAssociativeArrayWithUuid('name', $newGenres);

        $this->genreRepository->upsertMany($newGenres, ['name']);

        return $stored->toBase()->merge($newGenres)->pluck('id')->toArray();
    }
}
