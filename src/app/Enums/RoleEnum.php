<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum : string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
}
