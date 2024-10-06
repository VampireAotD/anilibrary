<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Models\Anime;
use App\Repositories\Contracts\FilterQuery;
use App\Repositories\Contracts\GetAll;
use Countable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

interface AnimeRepositoryInterface extends AnimeStatisticInterface, FilterQuery, Countable, GetAll
{
    public function updateOrCreate(array $data): Anime;

    public function findById(string $id): ?Anime;

    /**
     * @param array{titles: array<array{name: string}>, type: string, year: int} $data
     */
    public function findSimilar(array $data): ?Anime;

    public function findByUrl(string $url): ?Anime;

    public function findRandomAnime(): ?Anime;

    public function paginate(int $page, int $perPage = 1): LengthAwarePaginator;

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleasedAnime(): LazyCollection;

    /**
     * @return array<int, int>
     */
    public function getAddedAnimePerMonth(): array;
}
