<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

final readonly class ColumnFilter implements QueryFilterInterface
{
    /**
     * @param list<string> $columns
     */
    public function __construct(private array $columns = ['*'])
    {
    }

    public function filter(Builder $builder, Closure $next): Builder
    {
        $builder->select($this->columns);

        return $next($builder);
    }
}
