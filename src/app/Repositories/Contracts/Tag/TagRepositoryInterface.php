<?php

namespace App\Repositories\Contracts\Tag;

use App\Repositories\Contracts\FindByName;

/**
 * Interface TagRepositoryInterface
 * @package App\Repositories\Contracts\Tag
 */
interface TagRepositoryInterface extends FindByName
{
    /**
     * @param int $telegramId
     * @return array
     */
    public function findByTelegramId(int $telegramId): array;
}
