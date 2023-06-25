<?php

declare(strict_types=1);

namespace App\Telegram\State\Redis;

use App\Enums\Telegram\State\Redis\UserStateKeyEnum;
use Illuminate\Support\Facades\Redis;

/**
 * Class UserState
 * @package App\Telegram\State\Redis
 */
class UserState
{
    private const MAX_EXECUTED_COMMANDS_STORAGE_TTL = 3000;

    private function state()
    {
        return Redis::connection()->client();
    }

    private function stateKeys(int $telegramId): array
    {
        return array_map(fn(string $key) => sprintf('%s:%d', $key, $telegramId), UserStateKeyEnum::values());
    }

    public function setLastActiveAt(int $telegramId): void
    {
        [$lastActiveAtKey] = $this->stateKeys($telegramId);

        $this->state()->set($lastActiveAtKey, now()->unix());
    }

    public function addExecutedCommand(int $telegramId, string $command): void
    {
        [, $executedCommandsKey] = $this->stateKeys($telegramId);

        $commandsList = json_decode($this->state()->get($executedCommandsKey) ?? '');

        $commandsList[] = $command;

        $this->state()->setex(
            $executedCommandsKey,
            self::MAX_EXECUTED_COMMANDS_STORAGE_TTL,
            json_encode($commandsList)
        );
    }

    public function getLastExecutedCommand(int $telegramId): string
    {
        [, $executedCommandsKey] = $this->stateKeys($telegramId);

        $commandsList = json_decode($this->state()->get($executedCommandsKey) ?? '') ?? [];

        return end($commandsList) ?: '';
    }

    public function resetExecutedCommandsList(int $telegramId): void
    {
        [, $executedCommandsKey] = $this->stateKeys($telegramId);


        $this->state()->del($executedCommandsKey);
    }

    public function saveSearchResult(int $telegramId, array $result = []): void
    {
        [, , $searchResultKey] = $this->stateKeys($telegramId);

        $this->state()->del($searchResultKey);
        $this->state()->set($searchResultKey, json_encode($result));
    }

    public function getSearchResult(int $telegramId): array
    {
        [, , $searchResultKey] = $this->stateKeys($telegramId);

        return json_decode($this->state()->get($searchResultKey) ?? '') ?? [];
    }

    public function saveSearchResultPreview(int $telegramId, int $previewId): void
    {
        [, , , $searchResultPreviewKey] = $this->stateKeys($telegramId);

        $this->state()->set($searchResultPreviewKey, $previewId);
    }

    public function getSearchResultPreview(int $telegramId): string
    {
        [, , , $searchResultPreviewKey] = $this->stateKeys($telegramId);

        return $this->state()->get($searchResultPreviewKey);
    }

    public function removeSearchResultPreview(int $telegramId): void
    {
        [, , , $searchResultPreviewKey] = $this->stateKeys($telegramId);

        $this->state()->del($searchResultPreviewKey);
    }
}
