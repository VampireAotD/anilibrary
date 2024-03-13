<?php

declare(strict_types=1);

namespace App\Telegram\State\Redis;

use App\Enums\Telegram\State\Redis\UserStateKeyEnum;
use Illuminate\Support\Facades\Cache;

class UserState
{
    private const int EXECUTED_COMMANDS_STORAGE_TTL = 3000; // 5 minutes

    private function stateKey(UserStateKeyEnum $key, int $telegramId): string
    {
        return sprintf('%s:%d', $key->value, $telegramId);
    }

    public function setLastActiveAt(int $telegramId): void
    {
        Cache::add($this->stateKey(UserStateKeyEnum::WAS_LAST_ACTIVE_AT_KEY, $telegramId), now()->unix());
    }

    public function addExecutedCommand(int $telegramId, string $command): void
    {
        $key = $this->stateKey(UserStateKeyEnum::LAST_EXECUTED_COMMANDS_LIST_KEY, $telegramId);

        $commands   = Cache::get($key, []);
        $commands[] = $command;

        Cache::add(
            $key,
            $commands,
            now()->addSeconds(self::EXECUTED_COMMANDS_STORAGE_TTL)
        );
    }

    public function getLastExecutedCommand(int $telegramId): string
    {
        $commands = Cache::get($this->stateKey(UserStateKeyEnum::LAST_EXECUTED_COMMANDS_LIST_KEY, $telegramId), []);

        return end($commands) ?: '';
    }

    public function resetExecutedCommandsList(int $telegramId): void
    {
        Cache::delete($this->stateKey(UserStateKeyEnum::LAST_EXECUTED_COMMANDS_LIST_KEY, $telegramId));
    }

    public function saveSearchResult(int $telegramId, array $result = []): void
    {
        $key = $this->stateKey(UserStateKeyEnum::SEARCH_RESULT_KEY, $telegramId);

        Cache::delete($key);
        Cache::add($key, $result);
    }

    public function getSearchResult(int $telegramId): array
    {
        return Cache::get($this->stateKey(UserStateKeyEnum::SEARCH_RESULT_KEY, $telegramId), []);
    }

    public function saveSearchResultPreview(int $telegramId, int $previewId): void
    {
        Cache::add($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId), $previewId);
    }

    public function getSearchResultPreview(int $telegramId): ?string
    {
        return Cache::get($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId));
    }

    public function removeSearchResultPreview(int $telegramId): void
    {
        Cache::delete($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId));
    }
}
