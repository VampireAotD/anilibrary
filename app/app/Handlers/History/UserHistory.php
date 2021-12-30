<?php

namespace App\Handlers\History;

/**
 * Class UserHistory
 * @package App\Handlers\History
 */
class UserHistory
{
    public static array $history = [
        'lastActiveTime' => [],
        'executedCommands' => [],
    ];

    public static function addLastActiveTime(int $userId): void
    {
        self::$history['lastActiveTime'][$userId] = now()->format('Y-m-d H:i:s');
    }

    /**
     * @param int $userId
     * @param string $command
     * @return void
     */
    public static function addExecutedCommand(int $userId, string $command): void
    {
        self::$history['executedCommands'][$userId][] = $command;
    }

    /**
     * @param int $userId
     * @return false|string
     */
    public static function userLastExecutedCommand(int $userId): false|string
    {
        $userExecutedCommands = self::$history['executedCommands'][$userId] ?? [];

        return end($userExecutedCommands);
    }

    /**
     * @param int $userId
     * @return void
     */
    public static function clearUserHistory(int $userId): void
    {
        unset(self::$history['executedCommands'][$userId]);
    }

    /**
     * @return array
     */
    public static function getHistory(): array
    {
        return self::$history;
    }
}
