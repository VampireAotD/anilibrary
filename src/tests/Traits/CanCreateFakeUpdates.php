<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Str;
use WeStacks\TeleBot\Objects\Update;

trait CanCreateFakeUpdates
{
    private int $fakeTelegramId = -1;

    public function createFakeMessageUpdate(?int $telegramId = null, ?string $message = null): Update
    {
        $message    ??= Str::random();
        $telegramId ??= $this->fakeTelegramId;

        return new Update([
            'message' => [
                'from' => [
                    'id' => $telegramId,
                ],
                'chat' => [
                    'id' => $this->fakeTelegramId,
                ],
                'text' => $message,
            ],
        ]);
    }
}
