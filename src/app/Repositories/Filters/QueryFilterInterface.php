<?php

declare(strict_types=1);

namespace App\Repositories\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface QueryFilterInterface
{
    public function filter(Builder $builder, Closure $next): Builder;
}