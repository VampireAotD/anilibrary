<?php

declare(strict_types=1);

namespace App\Enums\Invitation;

use App\Enums\Concerns\ProvideLabels;

enum StatusEnum: string
{
    use ProvideLabels;

    case PENDING  = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::DECLINED => 'Declined',
        };
    }
}
