<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;

/**
 * Class UserService
 * @package App\Services
 */
readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function countUsers(): int
    {
        return $this->userRepository->count();
    }
}
