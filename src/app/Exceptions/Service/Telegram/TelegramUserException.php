<?php

declare(strict_types=1);

namespace App\Exceptions\Service\Telegram;

use Exception;

final class TelegramUserException extends Exception
{
    public static function userAlreadyRegistered(): self
    {
        return new self('User already registered');
    }
}
