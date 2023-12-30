<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

interface GetAll
{
    public function getAll(): Collection | LazyCollection;
}
