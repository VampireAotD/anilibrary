<?php

declare(strict_types=1);

namespace App\Enums\Validation;

/**
 * Enum SupportedUrlEnum
 * @package App\Enums\Validation
 */
enum SupportedUrlEnum : string
{
    case INVALID_URL     = 'Ссылка оказалась невалидной, попробуйте ещё раз';
    case UNSUPPORTED_URL = 'Бот не поддерживает парсинг данного сайта';
}
