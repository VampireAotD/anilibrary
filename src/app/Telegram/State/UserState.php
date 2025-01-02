<?php

declare(strict_types=1);

namespace App\Telegram\State;

use App\Enums\Telegram\State\UserStateKeyEnum;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class UserState
{
    /**
     * @param list<string> $result
     * @throws InvalidArgumentException
     */
    public function saveSearchResult(int $telegramId, array $result = []): void
    {
        $key = $this->stateKey(UserStateKeyEnum::SEARCH_RESULT_KEY, $telegramId);

        Cache::delete($key);
        Cache::add($key, $result);
    }

    /**
     * @return list<string>
     */
    public function getSearchResult(int $telegramId): array
    {
        return Cache::get($this->stateKey(UserStateKeyEnum::SEARCH_RESULT_KEY, $telegramId), default: []);
    }

    public function saveSearchResultPreview(int $telegramId, int $previewId): void
    {
        Cache::add($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId), $previewId);
    }

    public function getSearchResultPreview(int $telegramId): ?string
    {
        return Cache::get($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function removeSearchResultPreview(int $telegramId): void
    {
        Cache::delete($this->stateKey(UserStateKeyEnum::PREVIEW_SEARCH_RESULT_KEY, $telegramId));
    }

    private function stateKey(UserStateKeyEnum $key, int $telegramId): string
    {
        return sprintf('%s:%d', $key->value, $telegramId);
    }
}
