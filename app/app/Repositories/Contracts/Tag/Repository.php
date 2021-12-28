<?php

namespace App\Repositories\Contracts\Tag;

interface Repository
{
    /**
     * @param int $telegramId
     * @return array
     */
    public function findByTelegramId(int $telegramId): array;
}
