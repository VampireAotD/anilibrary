<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\Common\FindByName;

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
