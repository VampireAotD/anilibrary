<?php

declare(strict_types=1);

namespace App\Enums\Anime;

use App\Enums\Concerns\ProvideValues;

enum TypeEnum: string
{
    use ProvideValues;

    case SHOW  = 'ТВ Сериал';
    case MOVIE = 'Фильм';
}
