<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\DTO\Service\Telegram\User\TelegramUserDTO;
use App\DTO\Service\User\UserDTO;
use App\Exceptions\Service\Telegram\TelegramUserException;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final readonly class TelegramUserService
{
    public function __construct(private UserService $userService)
    {
    }

    public function generateSignature(array $data = []): string
    {
        $token = hash('sha256', config('nutgram.token'), true);

        $signature = collect($data)
            ->except('hash')
            ->map(fn(mixed $value, string $key): string => sprintf('%s=%s', $key, $value))
            ->values()
            ->sort()
            ->implode(PHP_EOL);

        return hash_hmac('sha256', $signature, $token);
    }

    public function upsert(TelegramUserDTO $dto): TelegramUser
    {
        return TelegramUser::updateOrCreate(['telegram_id' => $dto->telegramId], $dto->toArray());
    }

    /**
     * @throws TelegramUserException|Throwable
     */
    public function register(TelegramUserDTO $dto): TelegramUser
    {
        if (TelegramUser::where('telegram_id', $dto->telegramId)->exists()) {
            throw TelegramUserException::userAlreadyRegistered();
        }

        $domain = config('mail.temporary_domain');

        return DB::transaction(function () use ($dto, $domain): TelegramUser {
            $user = $this->userService->updateOrCreate(
                new UserDTO(
                    name    : (string) $dto->telegramId,
                    email   : "$dto->telegramId@$domain",
                    password: Str::random(),
                )
            );

            $user->markEmailAsVerified();

            return $user->telegramUser()->create($dto->toArray());
        });
    }

    public function assign(User $user, TelegramUserDTO $dto): void
    {
        $user->telegramUser()->updateOrCreate(['telegram_id' => $dto->telegramId], $dto->toArray());
    }
}
