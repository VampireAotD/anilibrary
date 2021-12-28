<?php

namespace App\Models\Traits;

/**
 * Trait CanProvideTableName
 * @package App\Models\Traits
 */
trait CanProvideTableName
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}
