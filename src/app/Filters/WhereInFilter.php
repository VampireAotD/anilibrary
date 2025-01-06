<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

final readonly class WhereInFilter implements QueryFilterInterface
{
    /**
     * @param list<mixed> $values
     */
    public function __construct(private string $column, private array $values)
    {
    }

    public function filter(Builder $builder, Closure $next): Builder
    {
        $builder->whereIn($this->column, $this->values);

        return $next($builder);
    }
}
