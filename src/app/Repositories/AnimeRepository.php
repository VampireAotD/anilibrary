<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @param string $id
     * @return Anime|null
     */
    public function findById(string $id): ?Anime
    {
        return $this->model()->find($id);
    }

    /**
     * @param string $title
     * @return Anime|null
     */
    public function findByTitle(string $title): ?Anime
    {
        return $this->model()->where('title', $title)->first();
    }

    /**
     * @param string $url
     * @return Anime|null
     */
    public function findByUrl(string $url): ?Anime
    {
        return $this->model()->where('url', $url)->first();
    }

    /**
     * @return Anime|null
     */
    public function findRandomAnime(): ?Anime
    {
        return $this->model()->inRandomOrder()->limit(1)->first();
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getAll(
        array $columns = ['*'],
        array $relations = ['image', 'genres', 'tags', 'voiceActing']
    ): Collection {
        return $this->model()->select($columns)->with($relations)->get();
    }

    /**
     * @param int    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param int    $currentPage
     * @return LengthAwarePaginator
     */
    public function paginate(
        int    $perPage = 1,
        array  $columns = ['*'],
        string $pageName = 'page',
        int    $currentPage = 1
    ): LengthAwarePaginator {
        return $this->model()->paginate($perPage, $columns, $pageName, $currentPage);
    }
}
