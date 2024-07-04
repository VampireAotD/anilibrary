<?php

declare(strict_types=1);

namespace Tests\Helpers\Faker\Providers;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use Faker\Provider\Base;

final class AnimeInformationProvider extends Base
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
        return $this->generator->randomElement(StatusEnum::values());
    }

    public function randomAnimeType(): string
    {
        return $this->generator->randomElement(TypeEnum::values());
    }
}
