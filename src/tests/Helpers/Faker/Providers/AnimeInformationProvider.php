<?php

declare(strict_types=1);

namespace Tests\Helpers\Faker\Providers;

use App\Enums\AnimeStatusEnum;
use Faker\Provider\Base;

class AnimeInformationProvider extends Base
{
    public function randomAnimeRating(int $min = 0, int $max = 10): float
    {
        return $this->generator->randomFloat(1, $min, $max);
    }

    public function randomAnimeEpisodes(): string
    {
        return (string) $this->generator->randomNumber();
    }

    public function randomAnimeStatus(): string
    {
        return $this->generator->randomElement(AnimeStatusEnum::values());
    }
}
