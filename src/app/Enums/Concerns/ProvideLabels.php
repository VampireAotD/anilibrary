<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

trait ProvideLabels
{
    abstract public function label(): string;

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $case) {
            $labels[$case->value ?? $case->name] = $case->label();
        }

        return $labels;
    }
}
