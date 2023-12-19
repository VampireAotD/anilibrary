<?php

declare(strict_types=1);

namespace Database\Factories;

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
            'status'   => $this->faker->randomAnimeStatus(),
            'episodes' => $this->faker->randomAnimeEpisodes(),
            'rating'   => $this->faker->randomAnimeRating(),
        ];
    }
}
