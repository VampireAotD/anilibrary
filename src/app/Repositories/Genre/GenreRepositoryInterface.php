<?php

declare(strict_types=1);

namespace App\Repositories\Genre;

use App\Repositories\Contracts\FindByName;
use App\Repositories\Contracts\UpsertMany;

/**
 * Interface GenreRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface GenreRepositoryInterface extends FindByName, UpsertMany
{
    //
}
