<?php

namespace App\Repositories\Contracts\Common;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface Paginate
 * @package App\Repositories\Contracts
 */
interface Paginate
{
    /**
     * @param int    $perPage
     * @param array  $columns
     * @param string $pageName
     * @param int    $currentPage
     * @return LengthAwarePaginator
     */
    public function paginate(
        int $perPage = 1,
        array $columns = ['*'],
        string $pageName = 'page',
        int $currentPage = 1
    ): LengthAwarePaginator;
}
