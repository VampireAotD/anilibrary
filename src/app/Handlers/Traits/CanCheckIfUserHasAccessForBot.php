<?php

declare(strict_types=1);

namespace App\Handlers\Traits;

/**
 * Trait CanConvertAnimeToCaption
 * @package App\Handlers\Traits
 */
trait CanCheckIfUserHasAccessForBot
{
    /**
     * @param int $id
     * @return bool
     */
    private function userHasAccess(int $id): bool
    {
        return $id === config('admin.id');
    }
}
