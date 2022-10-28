<?php

declare(strict_types=1);

namespace App\Services\Traits;

use Illuminate\Support\Str;

/**
 * Trait CanGenerateNamesArray
 * @package App\Services\Traits
 */
trait CanGenerateNamesArray
{
    /**
     * @param string[] $data
     * @return array
     */
    private function generateNamesArray(array $data): array
    {
        return array_map(fn(string $value) => ['id' => Str::orderedUuid()->toString(), 'name' => $value], $data);
    }
}
