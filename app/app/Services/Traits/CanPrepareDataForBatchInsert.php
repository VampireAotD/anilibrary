<?php

declare(strict_types=1);

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
    public function prepareArrayForInsert(array $data): array
    {
        if (!$this->arrayIsMultidimensional($data)) {
            $data = $this->prepareNamesArray($data);
        }

        array_walk($data, function (mixed &$value, string | int $key) {
            if (is_array($value)) {
                $value['id'] = Str::orderedUuid();
                return $value;
            }

            $value = [
                'id' => Str::orderedUuid(),
                $key => $value,
            ];
        });

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareNamesArray(array $data): array
    {
        array_walk($data, function (mixed &$value) {
            $value = [
                'name' => $value
            ];
        });

        return $data;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function arrayIsMultidimensional(array $data): bool
    {
        return count($data) !== count($data, COUNT_RECURSIVE);
    }
}
