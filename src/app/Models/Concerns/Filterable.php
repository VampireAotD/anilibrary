<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;

trait Filterable
{
    /**
     * @param Builder<$this>             $builder
     * @param list<QueryFilterInterface> $filters
     * @return Builder<$this>
     */
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        return Pipeline::send($builder)->through($filters)->via('filter')->thenReturn();
    }
}
