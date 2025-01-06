<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AnimeSynonym;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnimeSynonym>
 */
class AnimeSynonymFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
