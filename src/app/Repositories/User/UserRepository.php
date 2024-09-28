<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Builder<User>
     */
    protected Builder $query;

    public function __construct()
    {
        $this->query = User::query();
    }

    public function updateOrCreate(array $data): User
    {
        return $this->query->updateOrCreate(['email' => $data['email']], $data);
    }

    public function findOwner(): ?User
    {
        return $this->query->role(RoleEnum::OWNER)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query->where('email', $email)->first();
    }

    public function count(): int
    {
        return $this->query->count('id');
    }
}
