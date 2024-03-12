<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Telegram\User\CreateUserDTO;
use App\Models\TelegramUser;
use App\Models\User;
use App\Repositories\TelegramUser\TelegramUserRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class TelegramUserService
 * @package App\Services
 */
final readonly class TelegramUserService
{
    public function __construct(private TelegramUserRepositoryInterface $telegramUserRepository)
    {
    }

    public function upsert(CreateUserDTO $dto): TelegramUser
    {
        return $this->telegramUserRepository->upsert($dto->toArray());
    }

    public function createAndAttach(CreateUserDTO $dto, User $user): TelegramUser
    {
        return DB::transaction(function () use ($dto, $user) {
            $telegramUser = $this->upsert($dto);

            $telegramUser->user()->associate($user)->save();

            return $telegramUser;
        });
    }
}
