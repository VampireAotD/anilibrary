<?php

namespace App\Repositories;

use App\Enums\TagSeederEnum;
use App\Models\Tag;
use App\Repositories\Contracts\Tag\Repository;

/**
 * Class TagRepository
 * @package App\Repositories
 */
class TagRepository extends BaseRepository implements Repository
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return Tag::class;
    }

    /**
     * @param int $telegramId
     * @return array
     */
    public function findByTelegramId(int $telegramId): array
    {
        return match ($telegramId) {
            config('telebot.adminId') =>
            $this->query()->where('name', TagSeederEnum::ADMIN_TAG->value)->get()
                ->pluck('id')
                ->toArray(),
            config('telebot.firstAllowedUserId') =>
            $this->query()->where('name', TagSeederEnum::FIRST_MODERATOR_TAG->value)->get()
                ->pluck('id')
                ->toArray(),
            config('telebot.secondAllowedUserId') =>
            $this->query()->where('name', TagSeederEnum::SECOND_MODERATOR_TAG->value)->get()
                ->pluck('id')
                ->toArray(),
            default => []
        };
    }
}
