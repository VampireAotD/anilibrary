<?php

namespace App\Services\Traits;

use Illuminate\Support\Str;

/**
 * Trait CanPrepareDataForBatchInsert
 * @package App\Services\Traits
 */
trait CanPrepareDataForBatchInsert
{
    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data): array
    {
        return array_reduce($data, function (array $preparedData, string $name) {
            $preparedData[] = [
                'id' => Str::uuid(),
                'name' => $name
            ];

            return $preparedData;
        }, []);
    }
}
