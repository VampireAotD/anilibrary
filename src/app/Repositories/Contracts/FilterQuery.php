<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Repositories\Filters\QueryFilterInterface;

interface FilterQuery
{
    /**
     * @param array<QueryFilterInterface> $filters
     */
    public function withFilters(array $filters): static;
}