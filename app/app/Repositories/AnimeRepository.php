<?php

namespace App\Repositories;

use App\Models\Anime;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AnimeRepository
 * @package App\Repositories
 */
class AnimeRepository extends BaseRepository implements AnimeRepositoryInterface
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Anime::class;
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
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime
    {
        return $this->query()->inRandomOrder()->limit(1)->first();
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getAll(
        array $columns = ['*'],
        array $relations = ['image', 'genres', 'tags', 'voiceActing']
    ): Collection
    {
        return $this->query()->select($columns)->with($relations)->get();
    }
}
