<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Telegram\User\RegisterTelegramUserDTO;
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

    public function upsert(RegisterTelegramUserDTO $dto): TelegramUser
    {
        return $this->telegramUserRepository->upsert($dto->toArray());
    }

    /**
     * @throws Throwable
     */
    public function createAndAttach(User $user, RegisterTelegramUserDTO $dto): TelegramUser
    {
        return DB::transaction(function () use ($dto, $user): TelegramUser {
            /** @var TelegramUser $telegramUser */
            $telegramUser = TelegramUser::withTrashed()->updateOrCreate(
                ['telegram_id' => $dto->telegramId],
                $dto->toArray()
            );

            if ($telegramUser->trashed()) {
                $telegramUser->restore();
            }

            $telegramUser->user()->associate($user)->save();

            return $telegramUser;
        });
    }
}
