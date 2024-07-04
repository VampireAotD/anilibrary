<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Telegram\User\CreateUserDTO;
use App\Models\TelegramUser;
use App\Models\User;
use App\Repositories\TelegramUser\TelegramUserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class TelegramUserService
{
    public function __construct(private TelegramUserRepositoryInterface $telegramUserRepository)
    {
    }

    public function upsert(CreateUserDTO $dto): TelegramUser
    {
        return $this->telegramUserRepository->upsert($dto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function createAndAttach(CreateUserDTO $dto, User $user): TelegramUser
    {
        return DB::transaction(function () use ($dto, $user) {
            $telegramUser = $this->upsert($dto);

            $telegramUser->user()->associate($user)->save();

            return $telegramUser;
        });
    }
}
