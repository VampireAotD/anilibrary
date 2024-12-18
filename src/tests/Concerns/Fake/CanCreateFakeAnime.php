<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Collection;

trait CanCreateFakeAnime
{
    protected function createAnime(array $data = []): Anime
    {
        return Anime::factory()->createOneQuietly($data);
    }

    protected function createAnimeWithRelations(array $data = []): Anime
    {
        return Anime::factory()
                    ->has(Image::factory(), 'image')
                    ->has(AnimeUrl::factory(), 'urls')
                    ->has(AnimeSynonym::factory(), 'synonyms')
                    ->has(Genre::factory(), 'genres')
                    ->has(VoiceActing::factory(), 'voiceActing')
                    ->createOneQuietly($data);
    }

    /**
     * @return Collection<int, Anime>
     */
    protected function createAnimeCollection(int $quantity = 1): Collection
    {
        return Anime::factory(count: $quantity)->createManyQuietly();
    }

    /**
     * @return Collection<int, Anime>
     */
    protected function createAnimeCollectionWithRelations(int $quantity = 1): Collection
    {
        return Anime::factory(count: $quantity)
                    ->has(Image::factory(), 'image')
                    ->has(AnimeUrl::factory(), 'urls')
                    ->has(AnimeSynonym::factory(), 'synonyms')
                    ->has(Genre::factory(), 'genres')
                    ->has(VoiceActing::factory(), 'voiceActing')
                    ->createManyQuietly();
    }
}
