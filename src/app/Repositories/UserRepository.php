<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @return Builder|Model
     */
    protected function model(): Builder | Model
    {
        return User::query();
    }

    public function upsert(array $data): User
    {
        return $this->model()->updateOrCreate(['email' => $data['email']], $data);
    }

    public function findOwner(): ?User
    {
        return $this->model()->role(RoleEnum::OWNER->value)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model()->where('email', $email)->first();
    }
}
