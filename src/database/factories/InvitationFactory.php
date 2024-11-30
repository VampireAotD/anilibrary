<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Invitation\StatusEnum;
use App\Helpers\Registration;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email'      => $this->faker->unique()->email(),
            'status'     => $this->faker->randomElement(StatusEnum::cases()),
            'expires_at' => Registration::expirationDate(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::PENDING,
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::ACCEPTED,
        ]);
    }

    public function declined(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::DECLINED,
        ]);
    }
}
