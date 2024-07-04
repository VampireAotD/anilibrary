<?php

declare(strict_types=1);

namespace App\Enums\Anime;

use App\Enums\Concerns\ProvideValues;

enum StatusEnum: string
{
    use ProvideValues;

    case ONGOING  = 'Онгоинг';
    case ANNOUNCE = 'Анонс';
    case READY    = 'Вышел';
}
