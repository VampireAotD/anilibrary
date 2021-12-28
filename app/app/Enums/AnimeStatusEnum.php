<?php

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum AnimeStatusEnum
 * @package App\Enums
 */
enum AnimeStatusEnum: string
{
    use CanProvideCasesValues;

    case ONGOING = 'Онгоинг';
    case ANNOUNCE = 'Анонс';
    case READY = 'Вышел';
}
