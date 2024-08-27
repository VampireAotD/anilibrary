<?php

declare(strict_types=1);

namespace Feature\Services;

use App\DTO\Service\Telegram\User\TelegramUserDTO;
use App\Exceptions\Service\Telegram\TelegramUserException;
use App\Services\TelegramUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\Fake\CanCreateFakeUsers;
use Tests\TestCase;

class TelegramUserServiceTest extends TestCase
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

    public function testUserCannotBeRegisteredTwice(): void
    {
        $user = $this->createUserWithTelegramAccount()->telegramUser;

        $this->expectException(TelegramUserException::class);
        $this->expectExceptionMessage(TelegramUserException::userAlreadyRegistered()->getMessage());

        $this->telegramUserService->register(
            new TelegramUserDTO(
                telegramId: $user->telegram_id,
                firstName : $user->first_name,
                lastName  : $user->last_name,
                username  : $user->username
            )
        );
    }

    public function testRegisteredUserWillHaveTemporaryEmail(): void
    {
        $telegramUser = $this->telegramUserService->register(
            new TelegramUserDTO(
                telegramId: $this->faker->randomNumber(),
                firstName : $this->faker->firstName(),
                lastName  : $this->faker->lastName(),
                username  : $this->faker->userName()
            )
        );

        $this->assertNotNull($telegramUser->user);
        $this->assertTrue($telegramUser->user->has_temporary_email);
    }
}
