<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Telegram;

use App\Models\TelegramUser;
use App\Services\Telegram\TelegramUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class TelegramControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateFakeUsers;

    private TelegramUserService $telegramUserService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->telegramUserService = $this->app->make(TelegramUserService::class);
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

        return [...$data, 'hash' => $this->telegramUserService->generateSignature($data)];
    }

    public function testUserCannotAssignTelegramAccountWithoutTelegramSignature(): void
    {
        $this->actingAs($this->createUser())->post(route('telegram.assign'))->assertBadRequest();
    }

    public function testUserCannotAssignTelegramAccountWithInvalidTelegramSignature(): void
    {
        $this->actingAs($this->createUser())
             ->post(route('telegram.assign'), ['hash' => $this->faker->word])
             ->assertForbidden();
    }

    public function testUserCannotAssignTelegramAccountIfHeAlreadyHasOne(): void
    {
        $user = $this->createUser();
        $user->telegramUser()->save(TelegramUser::factory()->make());

        // RedirectIfHasAssignedUserMiddleware is applied to this route, it will handle this case
        $this->actingAs($user)
             ->post(route('telegram.assign'), $this->getValidTelegramData())
             ->assertRedirect()
             ->assertSessionHasErrors('message');
    }

    public function testUserCanAssignHisTelegramAccount(): void
    {
        $user = $this->createUser();
        $user->telegramUser()->save(TelegramUser::factory()->make());

        $this->actingAs($user)->post(route('telegram.assign'), $this->getValidTelegramData())->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->telegramUser);
    }
}
