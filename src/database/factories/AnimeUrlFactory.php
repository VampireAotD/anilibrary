<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AnimeUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnimeUrl>
 */
class AnimeUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
        ];
    }
}
