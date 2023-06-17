<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TelegramUser;
use App\Repositories\Contracts\TelegramUserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class TelegramUserRepository
 * @package App\Repositories
 */
class TelegramUserRepository extends BaseRepository implements TelegramUserRepositoryInterface
{
    /**
     * @param array $data
     * @return TelegramUser
     */
    public function upsert(array $data): TelegramUser
    {
        return DB::transaction(
            function () use ($data) {
                $telegramUser = $this->model()->with('user')->updateOrCreate(['username' => $data['username']], $data);

                if (!$telegramUser->user) {
                    $telegramUser->user()->create(
                        [
                            'telegram_user_id' => $telegramUser->id,
                            'password'         => bcrypt(Str::random()),
                        ]
                    );
                }

                return $telegramUser;
            }
        );
    }

    /**
     * @return Builder|TelegramUser
     */
    protected function model(): Builder | TelegramUser
    {
        return TelegramUser::query();
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
     * @param string $nickname
     * @return TelegramUser|null
     */
    public function findByNickname(string $nickname): ?TelegramUser
    {
        return $this->model()->where('nickname', $nickname)->first();
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
