<?php

declare(strict_types=1);

namespace Tests\Concerns\Fake;

use App\Telegram\Middleware\BotAccessMiddleware;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Testing\FakeNutgram;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait CanCreateFakeTelegramBot
{
    protected const int FAKE_TELEGRAM_ID = -1;

    protected readonly FakeNutgram $bot;

    protected function setUpFakeBot(): void
    {
        $this->bot = $this->app->make(Nutgram::class);

        // Disabling bot middlewares for testing other handlers
        // To test middleware attach it ot bot in test class
        $this->bot->withoutMiddleware([
            BotAccessMiddleware::class,
        ]);
    }

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
