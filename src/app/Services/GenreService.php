<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Genre\GenreRepositoryInterface;
use App\Traits\CanTransformArray;

/**
 * Class GenreService
 * @package App\Services
 */
class GenreService
{
    use CanTransformArray;

    public function __construct(private readonly GenreRepositoryInterface $genreRepository)
    {
    }

    /**
     * @param string[] $genres
     * @return string[]
     */
    public function sync(array $genres): array
    {
        $stored    = $this->genreRepository->findSimilarByNames($genres);
        $newGenres = array_diff($genres, $stored->pluck('name')->toArray());

        if (!$newGenres) {
            return $stored->pluck('id')->toArray();
        }

        $newGenres = $this->toAssociativeArrayWithUuid('name', $newGenres);

        $this->genreRepository->upsertMany($newGenres, ['name']);

        return $stored->toBase()->merge($newGenres)->pluck('id')->toArray();
    }
}
