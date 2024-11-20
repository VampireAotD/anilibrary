<?php

declare(strict_types=1);

namespace App\Enums\Invitation;

enum StatusEnum: string
{
    case PENDING  = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
}
