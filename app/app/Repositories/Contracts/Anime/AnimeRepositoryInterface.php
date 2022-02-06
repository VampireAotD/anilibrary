<?php

namespace App\Repositories\Contracts\Anime;

use App\Models\Anime;

/**
 * Interface AnimeRepositoryInterface
 * @package App\Repositories\Contracts\Anime
 */
interface AnimeRepositoryInterface
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
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime;
}
