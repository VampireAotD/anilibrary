<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\Service\User\UserDTO;
use App\Enums\RoleEnum;
use App\Models\User;

final readonly class UserService
{
    public function updateOrCreate(UserDTO $dto): User
    {
        return User::updateOrCreate(['email' => $dto->email], $dto->toArray());
    }

    public function getOwner(): ?User
    {
        return User::role(RoleEnum::OWNER)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }

    public function count(): int
    {
        return User::count();
    }
}
