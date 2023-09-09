<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Repositories\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;

trait Filterable
{
    /**
     * @param Builder                     $builder
     * @param array<QueryFilterInterface> $filters
     * @return Builder
     */
    public function scopeFilter(Builder $builder, array $filters): Builder
    {
        return Pipeline::send($builder)->through($filters)->via('filter')->thenReturn();
    }
}
