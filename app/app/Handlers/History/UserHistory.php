<?php

declare(strict_types=1);

namespace App\Handlers\History;

use Illuminate\Support\Facades\Redis;

/**
 * Class UserHistory
 * @package App\Handlers\History
 */
class UserHistory
{
    private const MAX_EXECUTED_COMMANDS_STORAGE_TTL = 3000;

    /**
     * @param int $userId
     * @return void
     */
    public static function addLastActiveTime(int $userId): void
    {
        [$lastActiveTime] = self::generateUserStorageName($userId);

        Redis::set($lastActiveTime, now()->format('Y-m-d H:i:s'));
    }

    /**
     * @param int $userId
     * @param string $command
     * @return void
     */
    public static function addExecutedCommand(int $userId, string $command): void
    {
        [, $executedCommands] = self::generateUserStorageName($userId);

        $commands = json_decode(Redis::get($executedCommands) ?? '');

        $commands[] = $command;

        Redis::set($executedCommands, json_encode($commands), 'EX', self::MAX_EXECUTED_COMMANDS_STORAGE_TTL);
    }

    /**
     * @param int $userId
     * @return false|string
     */
    public static function userLastExecutedCommand(int $userId): false|string
    {
        [, $executedCommands] = self::generateUserStorageName($userId);

        $commands = json_decode(Redis::get($executedCommands)) ?? [];

        return end($commands);
    }

    /**
     * @param int $userId
     * @return void
     */
    public static function clearUserExecutedCommandsHistory(int $userId): void
    {
        [, $executedCommands] = self::generateUserStorageName($userId);

        Redis::del($executedCommands);
    }

    /**
     * @param int $userId
     * @return array
     */
    private static function generateUserStorageName(int $userId): array
    {
        return [
            sprintf('history:lastActiveTime:%s', $userId),
            sprintf('history:executedCommands:%s', $userId),
        ];
    }
}
