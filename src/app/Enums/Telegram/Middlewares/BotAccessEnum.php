<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Middlewares;

/**
 * Enum BotAccessEnum
 * @package App\Enums\Telegram\Middlewares
 */
enum BotAccessEnum : string
{
    case ACCESS_DENIED_MESSAGE = 'У Вас нет полномочий пользоваться данным ботом';
}
