<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface FindByName
{
    /**
     * @param array<string> $names
     * @return Collection
     */
    public function findByNames(array $names): Collection;
}
