<?php

declare(strict_types=1);

namespace App\Repositories\TelegramUser;

use App\Models\TelegramUser;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TelegramUserRepository
 * @package App\Repositories
 */
class TelegramUserRepository extends BaseRepository implements TelegramUserRepositoryInterface
{
    /**
     * @return Builder|TelegramUser
     */
    protected function model(): Builder | TelegramUser
    {
        return TelegramUser::query();
    }

    public function upsert(array $data): TelegramUser
    {
        return $this->model()->updateOrCreate(['telegram_id' => $data['telegram_id']], $data);
    }

    /**
     * @param int $telegramId
     * @return TelegramUser|null
     */
    public function findByTelegramId(int $telegramId): ?TelegramUser
    {
        return $this->model()->where('telegram_id', $telegramId)->first();
    }

    /**
     * @param string $username
     * @return TelegramUser|null
     */
    public function findByUsername(string $username): ?TelegramUser
    {
        return $this->model()->where('username', $username)->first();
    }
}
