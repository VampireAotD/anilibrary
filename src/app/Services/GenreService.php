<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\GenreRepositoryInterface;
use App\Traits\CanGenerateNamesArray;

/**
 * Class GenreService
 * @package App\Services
 */
class GenreService
{
    use CanGenerateNamesArray;

    public function __construct(
        private readonly GenreRepositoryInterface $genreRepository
    ) {
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

        $newGenres = $this->generateNamesArray($newGenres);

        $this->genreRepository->upsertMany($newGenres, ['name']);

        return $stored->toBase()->merge($newGenres)->pluck('id')->toArray();
    }
}
