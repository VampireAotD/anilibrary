<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Repositories\Filters\PaginationFilter;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface Paginate
 * @package App\Repositories\Contracts
 */
interface Paginate
{
    public function paginate(PaginationFilter $filter): LengthAwarePaginator;
}
