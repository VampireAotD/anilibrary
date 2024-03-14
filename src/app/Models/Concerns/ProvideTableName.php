<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait ProvideTableName
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
