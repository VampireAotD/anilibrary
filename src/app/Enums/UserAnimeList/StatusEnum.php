<?php

declare(strict_types=1);

namespace App\Enums\UserAnimeList;

use App\Enums\Concerns\ProvideLabels;

enum StatusEnum: string
{
    use ProvideLabels;

    case PLAN_TO_WATCH = 'plan_to_watch';
    case WATCHING      = 'watching';
    case ON_HOLD       = 'on_hold';
    case COMPLETED     = 'completed';
    case DROPPED       = 'dropped';

    public function label(): string
    {
        return match ($this) {
            self::PLAN_TO_WATCH => 'Plan to watch',
            self::WATCHING      => 'Watching',
            self::ON_HOLD       => 'On hold',
            self::COMPLETED     => 'Completed',
            self::DROPPED       => 'Dropped',
        };
    }
}
