<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

/**
 * Interface UpsertMany
 * @package App\Repositories\Contracts\Common
 */
interface UpsertMany
{
    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, string | array $uniqueBy): int;
}