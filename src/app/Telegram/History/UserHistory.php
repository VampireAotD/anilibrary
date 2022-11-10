<?php

declare(strict_types=1);

namespace App\Telegram\History;

use Illuminate\Support\Facades\Redis;

/**
 * Class UserHistory
 * @package App\Telegram\History
 */
class UserHistory
{
    private const MAX_EXECUTED_COMMANDS_STORAGE_TTL = 3000;

    /**
     * @param int $userId
     * @return void
     */
    public function addLastActiveTime(int $userId): void
    {
        [$lastActiveTime] = $this->generateUserStorageName($userId);

        Redis::set($lastActiveTime, now()->format('Y-m-d H:i:s'));
    }

    /**
     * @param int    $userId
     * @param string $command
     * @return void
     */
    public function addExecutedCommand(int $userId, string $command): void
    {
        [, $executedCommands] = $this->generateUserStorageName($userId);

        $commands = json_decode(Redis::get($executedCommands) ?? '');

        $commands[] = $command;

        Redis::set($executedCommands, json_encode($commands), 'EX', self::MAX_EXECUTED_COMMANDS_STORAGE_TTL);
    }

    /**
     * @param int $userId
     * @return false|string
     */
    public function userLastExecutedCommand(int $userId): false | string
    {
        [, $executedCommands] = $this->generateUserStorageName($userId);

        $commands = json_decode(Redis::get($executedCommands) ?? '') ?? [];

        return end($commands);
    }

    /**
     * @param int $userId
     * @return void
     */
    public function clearUserExecutedCommandsHistory(int $userId): void
    {
        [, $executedCommands] = $this->generateUserStorageName($userId);

        Redis::del($executedCommands);
    }

    /**
     * @param int $userId
     * @return array
     */
    private function generateUserStorageName(int $userId): array
    {
        return [
            sprintf('history:lastActiveTime:%s', $userId),
            sprintf('history:executedCommands:%s', $userId),
        ];
    }
}
