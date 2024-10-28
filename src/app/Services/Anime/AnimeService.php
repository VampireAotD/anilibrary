<?php

declare(strict_types=1);

namespace App\Services\Anime;

use App\DTO\Service\Anime\AnimeDTO;
use App\DTO\Service\Anime\AnimePaginationDTO;
use App\DTO\Service\Anime\FindSimilarAnimeDTO;
use App\Filters\QueryFilterInterface;
use App\Jobs\Image\UploadJob;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Throwable;

final readonly class AnimeService
{
    /**
     * @throws Throwable
     */
    public function create(AnimeDTO $dto): Anime
    {
        return DB::transaction(function () use ($dto) {
            $anime = $this->updateOrCreate($dto);
            $this->upsertRelations($anime, $dto);

            return $anime;
        });
    }

    /**
     * @throws Throwable
     */
    public function update(Anime $anime, AnimeDTO $dto): Anime
    {
        return DB::transaction(function () use ($anime, $dto) {
            $anime->update($dto->toArray());
            $this->upsertRelations($anime, $dto);

            return $anime;
        });
    }

    public function findById(string $id): ?Anime
    {
        return Anime::find($id);
    }

    public function findSimilar(FindSimilarAnimeDTO $dto): ?Anime
    {
        return Anime::query()
                    ->where(function (Builder $query) use ($dto) {
                        $query->with('synonyms')
                              ->whereIn('title', $dto->titles)
                              ->orWhereHas('synonyms', fn(Builder $query) => $query->whereIn('name', $dto->titles));
                    })
                    ->where([
                        'type' => $dto->type,
                        'year' => $dto->year,
                    ])
                    ->first();
    }

    public function findByUrl(string $url): ?Anime
    {
        return Anime::withWhereHas('urls', fn($query) => $query->where('url', $url))->first();
    }

    public function randomAnime(): ?Anime
    {
        return Anime::inRandomOrder()->limit(1)->first();
    }

    /**
     * @param array<int, QueryFilterInterface> $filters
     * @return LazyCollection<int, Anime>
     */
    public function all(array $filters = []): LazyCollection
    {
        return Anime::filter($filters)->lazy();
    }

    public function paginate(AnimePaginationDTO $dto): LengthAwarePaginator
    {
        return Anime::filter($dto->filters)->paginate($dto->perPage, page: $dto->page);
    }

    public function unreleased(): LazyCollection
    {
        return Anime::unreleased()->with('urls')->lazy();
    }

    public function getParsedAnimePerMonth(): array
    {
        // Initial array of parsed anime per month, where month is a key, and value is a parsed anime count
        $initial = array_fill(0, 12, 0);

        $perMonth = Anime::countScrapedPerMonth()
                         ->pluck('per_month', 'month_number')
                         ->toArray();

        return array_replace($initial, $perMonth);
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getTenLatestAnime(): Collection
    {
        return Anime::with('image:id,path')->limit(10)->latest()->get();
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getTenMostPopularAnime(): Collection
    {
        return Anime::with('image:id,path')->limit(10)->latest('rating')->get();
    }

    /**
     * @return Collection<int, Anime>
     */
    public function getTenLatestReleasedAnime(): Collection
    {
        return Anime::released()->with('image:id,path')->limit(10)->latest()->get();
    }

    public function countAnime(): int
    {
        return Anime::count();
    }

    private function updateOrCreate(AnimeDTO $dto): Anime
    {
        return Anime::updateOrCreate(['title' => $dto->title], $dto->toArray());
    }

    private function upsertRelations(Anime $anime, AnimeDTO $dto): void
    {
        $anime->urls()->upsertRelated($dto->urls, ['url']);

        // Anime will always have default image path related to it
        // even if there is no actual record in DB, that's because
        // of Laravel withDefault method on image relation, so here
        // need to check if image has default path then it can be
        // replaced with new one
        if ($dto->image && $anime->image->is_default) {
            UploadJob::dispatch($anime, $dto->image);
        }

        if ($dto->synonyms) {
            $anime->synonyms()->upsertRelated($dto->synonyms, ['name']);
        }

        if ($dto->genres) {
            $anime->genres()->syncWithoutDetaching($dto->genres);
        }

        if ($dto->voiceActing) {
            $anime->voiceActing()->syncWithoutDetaching($dto->voiceActing);
        }
    }
}
