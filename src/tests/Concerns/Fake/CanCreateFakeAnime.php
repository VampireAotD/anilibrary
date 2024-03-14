<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Jobs\Elasticsearch\UpsertAnimeJob;
use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;

trait CanCreateFakeAnime
{
    protected function createAnime(array $data = []): Anime
    {
        Bus::fake();

        $anime = Anime::factory()->create($data);
        Bus::assertDispatched(UpsertAnimeJob::class);

        return $anime;
    }

    /**
     * @param int $quantity
     * @return Collection<Anime>
     */
    protected function createAnimeCollection(int $quantity = 1): Collection
    {
        Bus::fake();

        $collection = Anime::factory($quantity)->create();
        Bus::assertDispatched(UpsertAnimeJob::class);

        return $collection;
    }

    protected function createAnimeWithRelations(array $data = []): Anime
    {
        $anime = $this->createAnime($data);

        $anime->image()->save(Image::factory()->make());
        $anime->genres()->save(Genre::factory()->make());
        $anime->voiceActing()->save(VoiceActing::factory()->make());
        $anime->urls()->save(AnimeUrl::factory()->make());
        $anime->synonyms()->save(AnimeSynonym::factory()->make());

        return $anime;
    }

    /**
     * @return Collection<Anime>
     */
    protected function createAnimeCollectionWithRelations(int $quantity = 1): Collection
    {
        $collection = $this->createAnimeCollection($quantity);

        $collection->each(function (Anime $anime) {
            $anime->image()->save(Image::factory()->make());
            $anime->genres()->save(Genre::factory()->make());
            $anime->voiceActing()->save(VoiceActing::factory()->make());
            $anime->urls()->save(AnimeUrl::factory()->make());
            $anime->synonyms()->save(AnimeSynonym::factory()->make());
        });

        return $collection;
    }
}
