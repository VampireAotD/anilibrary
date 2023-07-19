<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait CanGenerateNamesArray
 * @package App\Traits
 */
trait CanTransformArray
{
    protected function toAssociativeArray(string $key, array $data): array
    {
        return array_map(fn(mixed $value) => [$key => $value], $data);
    }

    protected function toAssociativeArrayWithUuid(string $key, array $data): array
    {
        $associative = $this->toAssociativeArray($key, $data);

        return array_map(fn(array $data) => ['id' => Str::orderedUuid()->toString(), ...$data], $associative);
    }
}
