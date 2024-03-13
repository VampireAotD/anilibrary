<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Models\Anime;
use App\Repositories\Contracts\FilterQuery;
use App\Repositories\Contracts\GetAll;
use App\Repositories\Contracts\Quantity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

interface AnimeRepositoryInterface extends FilterQuery, Quantity, GetAll
{
    public function create(array $data): Anime;

    public function findById(string $id): ?Anime;

    /**
     * @param array<string> $data
     * @return Anime|null
     */
    public function findByTitleAndSynonyms(array $data): ?Anime;

    /**
     * @param string $url
     * @return Anime|null
     */
    public function findByUrl(string $url): ?Anime;

    /**
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime;

    public function paginate(int $page, int $perPage = 1): LengthAwarePaginator;

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleased(): LazyCollection;

    /**
     * @param int $limit
     * @return Collection<int, Anime>
     */
    public function getLatestAnime(int $limit = 10): Collection;

    /**
     * @return array<int, int>
     */
    public function getAddedAnimePerMonth(): array;
}
