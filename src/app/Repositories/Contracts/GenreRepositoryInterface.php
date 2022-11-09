<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\Common\FindByName;
use App\Repositories\Contracts\Common\UpsertMany;

/**
 * Interface GenreRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface GenreRepositoryInterface extends FindByName, UpsertMany
{
    //
}
