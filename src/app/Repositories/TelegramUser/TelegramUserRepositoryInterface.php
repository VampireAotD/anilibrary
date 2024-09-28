<?php

declare(strict_types=1);

namespace App\Repositories\TelegramUser;

use App\Models\TelegramUser;

interface TelegramUserRepositoryInterface
{
    public function updateOrCreate(array $data): TelegramUser;

    public function findByTelegramId(int $telegramId): ?TelegramUser;
}
