<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface FindByName
 * @package App\Repositories\Contracts
 */
interface FindByName
{
    /**
     * @param string $name
     * @return Model|null
     */
    public function findByName(string $name): ?Model;

    /**
     * @param array<string> $similarNames
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames): Collection;
}
