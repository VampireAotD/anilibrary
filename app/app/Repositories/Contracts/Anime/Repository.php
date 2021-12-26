<?php

namespace App\Repositories\Contracts\Anime;

use App\Models\Anime;
use App\Repositories\Contracts\FindById;

/**
 * Interface Repository
 * @package App\Repositories\Contracts\Anime
 */
interface Repository extends FindById
{
    /**
     * @param string $title
     * @param bool $useLike
     * @return Anime|null
     */
    public function findByTitle(string $title, bool $useLike = false): ?Anime;

    /**
     * @param string $url
     * @param bool $useLike
     * @return Anime|null
     */
    public function findByUrl(string $url, bool $useLike = false): ?Anime;

    /**
     * @return Anime
     */
    public function findRandomAnime(): Anime;
}
