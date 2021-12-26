<?php

namespace App\Repositories\Contracts\VoiceActing;

use App\Repositories\Contracts\FindById;
use Illuminate\Database\Eloquent\Collection;

interface Repository extends FindById
{
    /**
     * @param array $similarNames
     * @param array|string[] $attributes
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames, array $attributes = ['*']): Collection;
}
