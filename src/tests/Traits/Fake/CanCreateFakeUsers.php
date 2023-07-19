<?php

declare(strict_types=1);

namespace Tests\Traits\Fake;

use App\Enums\RoleEnum;
use App\Models\User;

trait CanCreateFakeUsers
{
    protected function createUser(array $data = []): User
    {
        return User::factory()->create($data);
    }

    protected function createOwner(array $data = []): User
    {
        return $this->createUser($data)->assignRole(RoleEnum::OWNER->value);
    }

    protected function createAdmin(array $data = []): User
    {
        return $this->createUser($data)->assignRole(RoleEnum::ADMIN->value);
    }
}
