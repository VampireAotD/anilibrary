<?php

declare(strict_types=1);

namespace App\Enums\UserAnimeList;

enum StatusEnum: string
{
    case PLAN_TO_WATCH = 'plan_to_watch';
    case WATCHING      = 'watching';
    case ON_HOLD       = 'on_hold';
    case COMPLETED     = 'completed';
    case DROPPED       = 'dropped';
}
