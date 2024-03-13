<?php

declare(strict_types=1);

namespace App\Facades\Telegram\State;

use App\Telegram\State\Redis\UserState;
use Illuminate\Support\Facades\Facade;

/**
 * @mixin UserState
 */
class UserStateFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'user-state';
    }
}
