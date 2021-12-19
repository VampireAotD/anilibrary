<?php

namespace App\Repositories\Contracts\TelegramUser;

use App\Models\TelegramUser;
use App\Repositories\Contracts\FindById;

/**
 * Interface Repository
 * @package App\Repositories\Contracts\TelegramUser
 */
interface Repository extends FindById
{
    /**
     * @param int $telegramId
     * @return TelegramUser|null
     */
    public function findByTelegramId(int $telegramId): ?TelegramUser;

    /**
     * @param string $nickname
     * @return TelegramUser|null
     */
    public function findByNickname(string $nickname): ?TelegramUser;

    /**
     * @param string $username
     * @return TelegramUser|null
     */
    public function findByUsername(string $username): ?TelegramUser;
}
