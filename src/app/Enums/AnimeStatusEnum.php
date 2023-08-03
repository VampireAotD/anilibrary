<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum AnimeStatusEnum
 * @package App\Enums\Telegram
 */
enum AnimeStatusEnum: string
{
    use CanProvideCasesValues;

    case ONGOING  = 'Онгоинг';
    case ANNOUNCE = 'Анонс';
    case READY    = 'Вышел';
}
