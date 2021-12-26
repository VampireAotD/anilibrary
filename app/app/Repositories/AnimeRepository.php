<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Repositories\Contracts\Anime\Repository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AnimeRepository
 * @package App\Repositories
 */
class AnimeRepository extends BaseRepository implements Repository
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Anime::class;
    }

    /**
     * @param string $uuid
     * @return Model|null
     */
    public function findById(string $uuid): ?Model
    {
        return $this->query()->find($uuid);
    }

    /**
     * @param string $title
     * @param bool $useLike
     * @return Anime|null
     */
    public function findByTitle(string $title, bool $useLike = false): ?Anime
    {
        if ($useLike) {
            return $this->query()->where('title', 'like', sprintf('%%%s%%'), $title)->first();
        }

        return $this->query()->where('title', $title)->first();
    }

    /**
     * @param string $url
     * @param bool $useLike
     * @return Anime|null
     */
    public function findByUrl(string $url, bool $useLike = false): ?Anime
    {
        if ($useLike) {
            return $this->query()->where('url', 'like', sprintf('%%%s%%'), $url)->first();
        }

        return $this->query()->where('url', $url)->first();
    }

    /**
     * @return Anime
     */
    public function findRandomAnime(): Anime
    {
        return $this->query()->inRandomOrder()->limit(1)->first();
    }
}
