<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Carbon;

final readonly class Registration
{
    /**
     * @todo make this as Carbon::macro() if laravel-ide-helper will support it
     * @see  https://github.com/barryvdh/laravel-ide-helper/issues/1162
     * @see  https://github.com/barryvdh/laravel-ide-helper/issues/1363
     */
    public static function expirationDate(): Carbon
    {
        return now()->addMinutes(config('auth.registration.expire'));
    }
}
