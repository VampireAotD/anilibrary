<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\CanProvideCasesValues;

/**
 * Enum TagEnum
 * @package App\Enums
 */
enum TagEnum : string
{
    use CanProvideCasesValues;

    case ADMIN_TAG = 'Мадара осилил';
}
