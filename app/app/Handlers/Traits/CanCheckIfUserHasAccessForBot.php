<?php

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
        $allowedIds = config('telebot.allowedTelegramIds');

        return in_array($id, $allowedIds, true);
    }
}
