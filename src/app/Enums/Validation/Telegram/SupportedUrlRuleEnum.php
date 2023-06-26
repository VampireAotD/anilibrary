<?php

declare(strict_types=1);

namespace App\Enums\Validation\Telegram;

/**
 * Class SupportedUrlRuleEnum
 * @package App\Enums\Validation\Telegram
 */
enum SupportedUrlRuleEnum : string
{
    case UNSUPPORTED_URL = 'Бот не поддерживает парсинг данного сайта';
}
