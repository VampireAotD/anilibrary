<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks;

interface CallbackInterface
{
    public static function command(): string;
}
