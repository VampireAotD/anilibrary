<?php

declare(strict_types=1);

namespace App\Facades\Telegram\State;

use App\Telegram\State\UserState;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin UserState
 */
final class UserStateFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return UserState::class;
    }
}
