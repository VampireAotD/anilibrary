<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use UnitEnum;

trait ProvideValues
{
    public static function values(): array
    {
        return array_map(fn(UnitEnum $unitEnum) => $unitEnum->value, self::cases());
    }
}