<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Middlewares;

/**
 * Enum ChatMemberStatusEnum
 * @package App\Enums\Telegram
 */
enum ChatMemberStatusEnum : string
{
    case MEMBER = 'member';
    case LEFT   = 'left';
    case KICKED = 'kicked';
}
