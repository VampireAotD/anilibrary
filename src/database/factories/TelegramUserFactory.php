<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TelegramUser>
 */
class TelegramUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'telegram_id' => $this->faker->randomNumber(),
            'first_name'  => $this->faker->firstName,
            'last_name'   => $this->faker->lastName,
            'username'    => $this->faker->userName,
        ];
    }
}
