<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function upsert(array $data): User;

    public function findOwner(): ?User;

    public function findByEmail(string $email): ?User;
}
