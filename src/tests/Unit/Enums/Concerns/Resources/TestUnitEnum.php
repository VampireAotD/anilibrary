<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Concerns\Resources;

use App\Enums\Concerns\ProvideLabels;

enum TestUnitEnum
{
    use ProvideLabels;

    case A;
    case B;

    public function label(): string
    {
        return match ($this) {
            self::A => 'First',
            self::B => 'Second',
        };
    }
}
