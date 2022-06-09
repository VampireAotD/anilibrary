<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Genre;
use App\Services\Traits\CanPrepareDataForBatchInsert;

/**
 * Class GenreService
 * @package App\Services
 */
class GenreService
{
    use CanPrepareDataForBatchInsert;

    /**
     * @param array $data
     * @return bool
     */
    public function batchInsert(array $data): bool
    {
        return Genre::insert($this->prepareArrayForInsert($data));
    }
}
