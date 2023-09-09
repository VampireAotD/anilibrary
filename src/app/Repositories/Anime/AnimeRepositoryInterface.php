<?php

declare(strict_types=1);

namespace App\Repositories\Anime;

use App\Models\Anime;
use App\Repositories\Contracts\FilterQuery;
use App\Repositories\Contracts\FindById;
use App\Repositories\Contracts\Paginate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

/**
 * Interface AnimeRepositoryInterface
 * @package App\Repositories\Contracts\Anime
 */
interface AnimeRepositoryInterface extends FindById, Paginate, FilterQuery
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

    public function getAll(): Collection;

    /**
     * @return LazyCollection<int, Anime>
     */
    public function getUnreleased(): LazyCollection;
}
