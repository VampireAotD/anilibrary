<?php

declare(strict_types=1);

namespace App\DTO\Service\Anime;

use App\Filters\QueryFilterInterface;

/**
 * Class AnimePaginationDTO
 * @package App\DTO\Service\Anime
 */
final readonly class AnimePaginationDTO
{
    /**
     * @param array<QueryFilterInterface> $filters
     */
    public function __construct(
        public int   $page,
        public int   $perPage = 1,
        public array $filters = [],
    ) {
    }
}