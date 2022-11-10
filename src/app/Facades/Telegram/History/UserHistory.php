<?php

declare(strict_types=1);

namespace App\Facades\Telegram\History;

use Illuminate\Support\Facades\Facade;

/**
 * Class UserHistory
 * @package App\Facades\Telegram\History
 *
 * @method static void addLastActiveTime(int $userId)
 * @method static void addExecutedCommand(int $userId, string $command)
 * @method static false|string userLastExecutedCommand(int $userId)
 * @method static void clearUserExecutedCommandsHistory(int $userId)
 *
 * @see     \App\Telegram\History\UserHistory
 */
class UserHistory extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'UserHistory';
    }
}
