<?php

namespace App\Repositories;

use App\Models\TelegramUser;
use App\Repositories\Contracts\TelegramUser\Repository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TelegramUserRepository
 * @package App\Repositories
 */
class TelegramUserRepository extends BaseRepository implements Repository
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return TelegramUser::class;
    }

    /**
     * @param string $uuid
     * @return Model|null
     */
    public function findById(string $uuid): ?Model
    {
        return $this->query()->where('uuid', $uuid)->first();
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
