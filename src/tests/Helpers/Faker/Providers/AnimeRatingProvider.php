<?php

declare(strict_types=1);

namespace Tests\Helpers\Faker\Providers;

use Faker\Provider\Base;

/**
 * Class AnimeRatingProvider
 * @package Tests\Helpers\Faker\Providers
 */
class AnimeRatingProvider extends Base
{
    public function randomAnimeRating(int $min = 0, int $max = 10): float
    {
        return $this->generator->randomFloat(1, $min, $max);
    }
}
