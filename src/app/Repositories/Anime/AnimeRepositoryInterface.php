<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Models\Anime;
use App\Repositories\Contracts\FilterQuery;
use App\Repositories\Contracts\FindById;
use App\Repositories\Contracts\Paginate;
use App\Repositories\Contracts\Quantity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

/**
 * Interface AnimeRepositoryInterface
 * @package App\Repositories\Contracts\Anime
 */
interface AnimeRepositoryInterface extends FindById, Paginate, FilterQuery, Quantity
{
    public function create(array $data): Anime;

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

    /**
     * @return Collection<int, Anime>
     */
    public function getAll(): Collection;

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
