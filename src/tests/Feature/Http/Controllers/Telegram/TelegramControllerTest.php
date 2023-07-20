<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Telegram;

use App\Models\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\Fake\CanCreateFakeUsers;

class TelegramControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    private function createValidSignature(array $data = []): string
    {
        $hashedToken = hash('sha256', config('telebot.bots.anilibrary.token'), true);

        $signature = collect($data)
            ->except('hash')
            ->map(fn(mixed $value, string $key) => sprintf('%s=%s', $key, $value))
            ->values()
            ->sort()
            ->implode(PHP_EOL);

        return hash_hmac('sha256', $signature, $hashedToken);
    }

    private function getValidTelegramData(): array
    {
        $data = [
            'id'         => $this->faker->randomNumber(),
            'auth_date'  => $this->faker->unixTime,
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'username'   => $this->faker->userName,
        ];

        return [...$data, 'hash' => $this->createValidSignature($data)];
    }

    public function testUserCannotConnectToTelegramWithoutTelegramSignature(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('telegram.assign'))
             ->assertBadRequest();
    }

    public function testUserCannotConnectToTelegramWithInvalidTelegramSignature(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('telegram.assign'), ['hash' => $this->faker->word])
             ->assertForbidden();
    }

    public function testUserCannotConnectToTelegramIfHeAlreadyHasAssignedTelegramUser(): void
    {
        $user = $this->createUser();
        $user->telegramUser()->save(TelegramUser::factory()->create());

        $this->actingAs($user)->post(route('telegram.assign'), $this->getValidTelegramData())->assertRedirect();
    }

    public function testUserCanConnectHisTelegram(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)->post(route('telegram.assign'), $this->getValidTelegramData())->assertRedirect();

        $user->refresh();

        $this->assertNotNull($user->telegramUser);
    }

    public function testUserCanDetachHisTelegram(): void
    {
        $user         = $this->createUser();
        $telegramUser = $user->telegramUser()->save(TelegramUser::factory()->create());

        $this->assertEquals($user->id, $telegramUser->user_id);

        $this->actingAs($user)->delete(route('telegram.detach'))->assertRedirect();

        $user->refresh();
        $telegramUser->refresh();

        $this->assertNull($telegramUser->user_id);
        $this->assertNull($user->telegramUser);
    }
}
