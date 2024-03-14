<?php

declare(strict_types=1);

namespace Tests\Concerns;

trait CanCreateFakeUpdates
{
    protected const int FAKE_TELEGRAM_ID = -1;

    public function createFakeTextMessageUpdateData(?string $message = null, array $from = [], array $chat = []): array
    {
        $defaultFrom = ['id' => self::FAKE_TELEGRAM_ID];
        $defaultChat = ['id' => self::FAKE_TELEGRAM_ID];

        $from = array_merge($defaultFrom, $from);
        $chat = array_merge($defaultChat, $chat);

        return [
            'text' => $message,
            'from' => $from,
            'chat' => $chat,
        ];
    }
}
