<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserAnimeList\StatusEnum;
use App\Models\Pivots\UserAnimeList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserAnimeList>
 */
class UserAnimeListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'   => $this->faker->randomElement(StatusEnum::cases()),
            'episodes' => $this->faker->randomAnimeEpisodes(),
            'rating'   => $this->faker->randomAnimeRating(),
        ];
    }
}
