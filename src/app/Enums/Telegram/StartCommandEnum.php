<?php

declare(strict_types=1);

namespace App\Enums\Telegram;

/**
 * Enum StartCommandEnum
 * @package App\Enums\Telegram
 */
enum StartCommandEnum : string
{
    case WELCOME_MESSAGE = "Вас приветствует AniLibrary Bot!\xF0\x9F\x91\x8B\nПожалуйста, выберете интересующее Вас действие:";
}
