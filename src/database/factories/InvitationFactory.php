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

    /**
     * Indicate that the model's status should be pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::PENDING,
        ]);
    }

    /**
     * Indicate that the model's status should be accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::ACCEPTED,
        ]);
    }

    /**
     * Indicate that the model's status should be declined.
     */
    public function declined(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => StatusEnum::DECLINED,
        ]);
    }
}
