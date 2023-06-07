<?php

declare(strict_types=1);

namespace App\Enums\Telegram;

enum BotAccessEnum : string
{
    case ACCESS_DENIED_MESSAGE = "\xF0\x9F\x98\xBF К сожалению, у Вас нету полномочий пользоваться данным ботом";
}
