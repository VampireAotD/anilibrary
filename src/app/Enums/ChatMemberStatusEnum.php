<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Enum ChatMemberStatusEnum
 * @package App\Enums
 */
enum ChatMemberStatusEnum: string
{
    case MEMBER = 'member';
    case KICKED = 'kicked';
}
