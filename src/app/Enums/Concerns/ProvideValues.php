<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use UnitEnum;

trait ProvideValues
{
    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn(UnitEnum $unitEnum) => $unitEnum->value, self::cases());
    }
}
