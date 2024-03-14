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
     * @param array<string> $genres
     * @return array<string>
     */
    public function sync(array $genres): array
    {
        $stored     = $this->genreRepository->findByNames($genres);
        $genreNames = collect($genres)->pluck('name');

        // Find difference between stored genres and new ones
        $newGenres = $genreNames->diff($stored->pluck('name'))->map(fn(string $genre) => ['name' => $genre]);

        // If there is new genres - upsert them and get their ids
        if ($newGenres->isNotEmpty()) {
            $this->genreRepository->upsertMany($newGenres->toArray(), ['name']);
            $stored = $this->genreRepository->findByNames($genres);
        }

        return $stored->pluck('id')->toArray();
    }
}
