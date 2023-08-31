<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait WithoutPrimaryKey
{
    public function getKeyName(): ?string
    {
        return null;
    }

    public function getIncrementing(): false
    {
        return false;
    }
}
