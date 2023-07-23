<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Commands;

/**
 * Enum StartCommandEnum
 * @package App\Enums\Telegram
 */
enum StartCommandEnum: string
{
    case WELCOME_MESSAGE = "Вас приветствует AniLibrary Bot!\nПожалуйста, выберите интересующее Вас действие:";
}
