<?php

declare(strict_types=1);

namespace App\Repositories\Params;

/**
 * Class PaginationParams
 * @package App\Repositories\Params
 */
readonly class PaginationParams
{
    public function __construct(
        public int $page,
        public int $perPage = 1
    ) {
    }
}
