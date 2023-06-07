<?php

declare(strict_types=1);

namespace App\Enums\Telegram;

/**
 * Enum ChatMemberStatusEnum
 * @package App\Enums
 */
enum ChatMemberStatusEnum : string
{
    case MEMBER = 'member';
    case LEFT   = 'left';
    case KICKED = 'kicked';
}
