<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Class TelegramUserService
 * @package App\Services
 */
class TelegramUserService
{
    public function register(array $data): bool
    {
        $telegramUser = TelegramUser::create($data);

        return User::create([
            'telegram_user_id' => $telegramUser->id,
            'password' => Str::random(),
        ])->save();
    }
}
