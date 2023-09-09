<?php

declare(strict_types=1);

namespace App\Repositories\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ColumnFilter
 * @package App\Filters\Query\Filters
 */
readonly class ColumnFilter implements QueryFilterInterface
{
    public function __construct(private array $columns = ['*'])
    {
    }

    public function filter(Builder $builder, Closure $next): Builder
    {
        $builder->select($this->columns);

        return $next($builder);
    }
}
