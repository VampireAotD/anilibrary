<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnimeStatusEnum;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'    => $this->faker->title,
            'status'   => $this->faker->randomElement(AnimeStatusEnum::values()),
            'episodes' => (string) $this->faker->randomNumber(),
            'rating'   => $this->faker->randomAnimeRating(),
        ];
    }
}
