<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\Quantity;

interface UserRepositoryInterface extends Quantity
{
    public function upsert(array $data): User;

    public function findOwner(): ?User;

    public function findByEmail(string $email): ?User;
}
