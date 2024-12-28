<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Concerns;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Enums\Concerns\Resources\TestBackedEnum;
use Tests\Unit\Enums\Concerns\Resources\TestUnitEnum;

final class ProvideLabelsTest extends TestCase
{
    public function testCanProvideLabelsForUnitEnum(): void
    {
        $labels = TestUnitEnum::labels();

        $this->assertIsArray($labels);
        $this->assertArrayHasKey(TestUnitEnum::A->name, $labels);
        $this->assertArrayHasKey(TestUnitEnum::B->name, $labels);
    }

    public function testCanProvideLabelsForBackedEnum(): void
    {
        $labels = TestBackedEnum::labels();

        $this->assertIsArray($labels);
        $this->assertArrayHasKey(TestBackedEnum::A->value, $labels);
        $this->assertArrayHasKey(TestBackedEnum::B->value, $labels);
    }
}
