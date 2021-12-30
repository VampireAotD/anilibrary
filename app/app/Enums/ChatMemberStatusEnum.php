<?php

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
