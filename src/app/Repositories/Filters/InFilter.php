<?php

declare(strict_types=1);

namespace App\Repositories\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class InFilter
 * @package App\Filters\Query\Filters
 */
readonly class InFilter implements QueryFilterInterface
{
    public function __construct(private string $column, private array $values)
    {
    }

    public function filter(Builder $builder, Closure $next): Builder
    {
        $builder->whereIn($this->column, $this->values);

        return $next($builder);
    }
}
