<?php

namespace App\Enums\Traits;

/**
 * Enum AnimeHandlerEnum
 * @package App\Enums\Traits
 */
trait CanProvideCasesValues
{
    public static function values(): array
    {
        return array_map(fn(\UnitEnum $unitEnum) => $unitEnum->value, self::cases());
    }
}
