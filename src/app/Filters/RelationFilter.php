<?php

declare(strict_types=1);

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

final readonly class RelationFilter implements QueryFilterInterface
{
    public function __construct(private array $relations = [])
    {
    }

    public function filter(Builder $builder, Closure $next): Builder
    {
        if ($this->relations) {
            $builder->with($this->relations);
        }

        return $next($builder);
    }
}
