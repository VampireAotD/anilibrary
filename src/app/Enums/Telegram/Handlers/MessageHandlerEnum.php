<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Handlers;

enum MessageHandlerEnum : string
{
    case PROVIDE_URL = 'Укажите ссылку для парсинга сайта (это может занять несколько минут)';
}
