<?php

namespace App\Repositories\TelegramUser;

use App\Models\TelegramUser;

/**
 * Interface AnimeRepositoryInterface
 * @package App\Repositories\Contracts\TelegramUser
 */
interface TelegramUserRepositoryInterface
{
    /**
     * @param array $data
     * @return TelegramUser
     */
    public function upsert(array $data): TelegramUser;

    /**
     * @param int $telegramId
     * @return TelegramUser|null
     */
    public function findByTelegramId(int $telegramId): ?TelegramUser;

    /**
     * @param string $username
     * @return TelegramUser|null
     */
    public function findByUsername(string $username): ?TelegramUser;
}
