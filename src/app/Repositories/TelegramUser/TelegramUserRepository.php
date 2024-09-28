<?php

declare(strict_types=1);

namespace App\Repositories\TelegramUser;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Builder;

class TelegramUserRepository implements TelegramUserRepositoryInterface
{
    /**
     * @var Builder<TelegramUser>
     */
    protected Builder $query;

    public function __construct()
    {
        $this->query = TelegramUser::query();
    }

    public function updateOrCreate(array $data): TelegramUser
    {
        return $this->query->updateOrCreate(['telegram_id' => $data['telegram_id']], $data);
    }

    public function findByTelegramId(int $telegramId): ?TelegramUser
    {
        return $this->query->where('telegram_id', $telegramId)->first();
    }
}
