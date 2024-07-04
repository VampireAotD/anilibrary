<?php

declare(strict_types=1);

namespace App\Services;

use App\Filters\QueryFilterInterface;
use App\Models\Genre;
use App\Repositories\Genre\GenreRepositoryInterface;
use Illuminate\Support\LazyCollection;

final readonly class GenreService
{
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
     * @param array<array{name: string}> $genres
     * @return array<string> Array of genre ids
     */
    public function sync(array $genres): array
    {
        // TODO try to reduce queries

        $names  = collect($genres)->pluck('name');
        $stored = $this->genreRepository->findByNames($names->toArray());

        // Find difference between stored genres and new ones
        $newGenres = $names->diff($stored->pluck('name'))->map(fn(string $genre) => ['name' => $genre]);

        // If there is new genres - upsert them and get their ids
        if ($newGenres->isNotEmpty()) {
            $this->genreRepository->upsertMany($newGenres->toArray(), ['name']);
            $stored = $this->genreRepository->findByNames($names->toArray());
        }

        return $stored->pluck('id')->toArray();
    }
}
