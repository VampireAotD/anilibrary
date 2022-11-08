<?php

namespace Database\Factories;

use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoiceActing>
 */
class VoiceActingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
