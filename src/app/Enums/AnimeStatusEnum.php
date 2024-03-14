<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\ProvideValues;

enum AnimeStatusEnum: string
{
    use ProvideValues;

    case ONGOING  = 'Онгоинг';
    case ANNOUNCE = 'Анонс';
    case READY    = 'Вышел';
}
