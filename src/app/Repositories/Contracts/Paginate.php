<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Repositories\Params\PaginationParams;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface Paginate
 * @package App\Repositories\Contracts
 */
interface Paginate
{
    public function paginate(PaginationParams $filter): LengthAwarePaginator;
}
