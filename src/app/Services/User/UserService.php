<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\Service\User\UserDTO;
use App\Enums\RoleEnum;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class UserService
{
    public function updateOrCreate(UserDTO $dto): User
    {
        return User::updateOrCreate(['email' => $dto->email], $dto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function register(UserDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            $user = $this->updateOrCreate($dto);

            Invitation::whereEmail($user->email)->delete();

            return $user;
        });
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
