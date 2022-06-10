<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TelegramUser;
use App\Repositories\Contracts\TelegramUser\TelegramUserRepositoryInterface;

/**
 * Class TelegramUserRepository
 * @package App\Repositories
 */
class TelegramUserRepository extends BaseRepository implements TelegramUserRepositoryInterface
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return TelegramUser::class;
    }

    /**
     * @param int $telegramId
     * @return TelegramUser|null
     */
    public function findByTelegramId(int $telegramId): ?TelegramUser
    {
        return $this->query()->where('telegram_id', $telegramId)->first();
    }

    /**
     * @param string $nickname
     * @return TelegramUser|null
     */
    public function findByNickname(string $nickname): ?TelegramUser
    {
        return $this->query()->where('nickname', $nickname)->first();
    }

    /**
     * @param string $username
     * @return TelegramUser|null
     */
    public function findByUsername(string $username): ?TelegramUser
    {
        return $this->query()->where('username', $username)->first();
    }
}
