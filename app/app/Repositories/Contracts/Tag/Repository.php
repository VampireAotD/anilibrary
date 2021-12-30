<?php

namespace App\Repositories\Contracts\Tag;

use App\Models\Tag;

interface Repository
{
    /**
     * @param int $telegramId
     * @return array
     */
    public function findByTelegramId(int $telegramId): array;

    /**
     * @param string $name
     * @param array $columns
     * @return Tag|null
     */
    public function findByName(string $name, array $columns = ['*']): ?Tag;
}
