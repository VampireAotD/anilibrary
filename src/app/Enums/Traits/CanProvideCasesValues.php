<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use UnitEnum;

/**
 * Trait CanProvideCasesValues
 * @package App\Enums\Traits
 */
trait CanProvideCasesValues
{
    public static function values(): array
    {
        return array_map(fn(UnitEnum $unitEnum) => $unitEnum->value, self::cases());
    }
}
