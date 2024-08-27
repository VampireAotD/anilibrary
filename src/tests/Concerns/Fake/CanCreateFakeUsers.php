<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Enums\RoleEnum;
use App\Models\TelegramUser;
use App\Models\User;

trait CanCreateFakeUsers
{
    protected function createUser(array $data = []): User
    {
        return User::factory()->create($data);
    }

    protected function createUnverifiedUser(array $data = []): User
    {
        return User::factory()->unverified()->create($data);
    }

    protected function createOwner(array $data = []): User
    {
        return $this->createUser($data)->assignRole(RoleEnum::OWNER);
    }

    protected function createAdmin(array $data = []): User
    {
        return $this->createUser($data)->assignRole(RoleEnum::ADMIN);
    }

    protected function createUserWithTelegramAccount(): User
    {
        $user = $this->createUser();

        $user->telegramUser()->save(TelegramUser::factory()->make());

        return $user;
    }
}
