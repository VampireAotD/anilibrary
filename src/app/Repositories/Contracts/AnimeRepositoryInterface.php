<?php

namespace App\Repositories\Contracts;

use App\Models\Anime;
use App\Repositories\Contracts\Common\Paginate;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface AnimeRepositoryInterface
 * @package App\Repositories\Contracts\Anime
 */
interface AnimeRepositoryInterface extends Paginate
{
    /**
     * @param string $title
     * @param bool   $useLike
     * @return Anime|null
     */
    public function findByTitle(string $title, bool $useLike = false): ?Anime;

    /**
     * @param string $url
     * @param bool   $useLike
     * @return Anime|null
     */
    public function findByUrl(string $url, bool $useLike = false): ?Anime;

    /**
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime;

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getAll(
        array $columns = ['*'],
        array $relations = ['image', 'genres', 'tags', 'voiceActing']
    ): Collection;
}
