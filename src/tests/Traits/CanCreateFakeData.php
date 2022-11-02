<?php

declare(strict_types=1);

namespace Tests\Traits;

use App\Models\Anime;
use App\Models\Genre;
use App\Models\Image;
use App\Models\VoiceActing;

/**
 * Trait CanCreateFakeData
 * @package Tests\Traits
 */
trait CanCreateFakeData
{
    /**
     * @param int $count
     * @return mixed
     */
    public function createRandomAnimeWithRelations(int $count = 1): mixed
    {
        return Anime::factory($count)->create()->each(
            function (Anime $anime) {
                $anime->image()->save(Image::factory()->make());
                $anime->genres()->save(Genre::factory()->make());
                $anime->voiceActing()->save(VoiceActing::factory()->make());
            }
        );
    }
}
