<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\Anime;
use App\Models\AnimeSynonym;
use App\Models\AnimeUrl;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;
use Illuminate\Support\Collection;

/**
 * Trait CanCreateFakeData
 * @package Tests\Traits
 */
trait CanCreateFakeData
{
    /**
     * @param int $count
     * @return Collection<Anime>
     */
    public function createRandomAnimeWithRelations(int $count = 1): Collection
    {
        return Anime::factory($count)->create()->each(
            function (Anime $anime) {
                $anime->image()->save(Image::factory()->make());
                $anime->genres()->save(Genre::factory()->make());
                $anime->voiceActing()->save(VoiceActing::factory()->make());
                $anime->urls()->save(AnimeUrl::factory()->make());
                $anime->synonyms()->save(AnimeSynonym::factory()->make());
            }
        )->toBase();
    }
}
