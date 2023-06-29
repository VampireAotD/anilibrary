<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Telegram\CreateUserDTO;
use App\Models\TelegramUser;
use App\Repositories\Contracts\TelegramUserRepositoryInterface;

/**
 * Class TelegramUserService
 * @package App\Services
 */
readonly class TelegramUserService
{
    public function __construct(private TelegramUserRepositoryInterface $telegramUserRepository)
    {
    }

    public function register(CreateUserDTO $dto): TelegramUser
    {
        if ($user = $this->telegramUserRepository->findByTelegramId($dto->telegramId)) {
            return $user;
        }

        return $this->telegramUserRepository->upsert($dto->toArray());
    }
}
