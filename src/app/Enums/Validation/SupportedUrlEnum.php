<?php

declare(strict_types=1);

namespace App\Enums\Validation;

/**
 * Enum SupportedUrlEnum
 * @package App\Enums\Validation
 */
enum SupportedUrlEnum : string
{
    case UNSUPPORTED_URL = 'Бот не поддерживает парсинг данного сайта';
}
