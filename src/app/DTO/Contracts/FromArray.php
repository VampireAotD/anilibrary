<?php

declare(strict_types=1);

namespace App\DTO\Contracts;

interface FromArray
{
    /**
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self;
}
