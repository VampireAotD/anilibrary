<?php

declare(strict_types=1);

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class PaginationFilter
 * @package App\Repositories\Filters
 */
class PaginationFilter
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage = 1,
        protected array     $columns = [],
        protected array     $relations = [],
        protected array     $whereIn = []
    ) {
    }

    public function withColumns(string ...$columns): self
    {
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

    public function withRelations(string ...$relations): self
    {
        $this->relations = array_merge($this->relations, $relations);
        return $this;
    }

    public function withWhereIn(string $key, array $values): self
    {
        $this->whereIn[$key] = $values;
        return $this;
    }

    public function apply(Builder $builder): Builder
    {
        if (!$this->columns) {
            $this->columns = ['*'];
        }

        return $builder->select($this->columns)
                       ->when($this->relations, fn(Builder $builder) => $builder->with($this->relations))
                       ->when($this->whereIn, function (Builder $builder) {
                           foreach ($this->whereIn as $key => $values) {
                               $builder->whereIn($key, $values);
                           }
                       });
    }
}
