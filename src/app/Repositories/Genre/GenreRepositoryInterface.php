<?php

declare(strict_types=1);

namespace App\Repositories\Genre;

use App\Repositories\Contracts\FilterQuery;
use App\Repositories\Contracts\FindByName;
use App\Repositories\Contracts\GetAll;
use App\Repositories\Contracts\UpsertMany;

interface GenreRepositoryInterface extends FindByName, UpsertMany, GetAll, FilterQuery
{
    //
}
