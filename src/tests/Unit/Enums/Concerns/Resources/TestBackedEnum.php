<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Concerns\Resources;

use App\Enums\Concerns\ProvideLabels;

enum TestBackedEnum: string
{
    use ProvideLabels;

    case A = 'a';
    case B = 'b';

    public function label(): string
    {
        return match ($this) {
            self::A => 'A',
            self::B => 'B',
        };
    }
}
