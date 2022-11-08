<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait CanGenerateNamesArray
 * @package App\Traits
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
