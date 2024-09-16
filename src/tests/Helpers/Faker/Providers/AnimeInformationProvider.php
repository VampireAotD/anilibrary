<?php

declare(strict_types=1);

namespace Tests\Helpers\Faker\Providers;

use App\Enums\Anime\StatusEnum;
use App\Enums\Anime\TypeEnum;
use Faker\Provider\Base;

final class AnimeInformationProvider extends Base
{
    public function randomAnimeImage(?string $content = null): string
    {
        $extension = $this->generator->randomElement(['png', 'jpg', 'jpeg', 'webp', 'gif']);

        return sprintf("data:image/%s;base64,%s", $extension, base64_encode($content ?? $this->generator->sentence));
    }

    public function randomAnimeRating(int $min = 0, int $max = 10): float
    {
        return $this->generator->randomFloat(1, $min, $max);
    }

    public function randomAnimeEpisodes(): string
    {
        return (string) $this->generator->randomNumber();
    }

    public function randomAnimeStatus(): StatusEnum
    {
        return $this->generator->randomElement(StatusEnum::cases());
    }

    public function randomAnimeType(): TypeEnum
    {
        return $this->generator->randomElement(TypeEnum::cases());
    }
}
