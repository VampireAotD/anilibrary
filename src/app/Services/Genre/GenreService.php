<?php

declare(strict_types=1);

namespace App\Services\Genre;

use App\Filters\QueryFilterInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

final readonly class GenreService
{
    /**
     * @param list<QueryFilterInterface> $filters
     * @return LazyCollection<int, Genre>
     */
    public function all(array $filters = []): LazyCollection
    {
        return Genre::filter($filters)->lazy();
    }

    /**
     * @param array<array{name: string}> $genres
     * @return list<string> Array of genre ids
     */
    public function sync(array $genres): array
    {
        $names  = collect($genres)->pluck('name');
        $stored = $this->findByNames($names->toArray());

        // Find difference between stored genres and new ones
        $newGenres = $names->diff($stored->pluck('name'))->map(
            static fn(string $genre) => ['name' => $genre]
        );

        // If there is new genres - upsert them and get their ids
        if ($newGenres->isNotEmpty()) {
            Genre::upsert($newGenres->toArray(), ['name']);
            $stored = $this->findByNames($names->toArray());
        }

        return $stored->pluck('id')->toArray();
    }

    /**
     * @param list<string> $names
     */
    private function findByNames(array $names): Collection
    {
        return Genre::select(['id', 'name'])->whereIn('name', $names)->get();
    }
}
