<?php

declare(strict_types=1);

namespace Tests\Feature\Telegram\Middleware;

use App\Telegram\Middleware\BotAccessMiddleware;
use SergiX44\Nutgram\Nutgram;
use Tests\Concerns\Fake\CanCreateFakeTelegramBot;
use Tests\TestCase;

class BotAccessMiddlewareTest extends TestCase
{
    use CanCreateFakeTelegramBot;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeBot();
    }

    public function testUserCannotInteractWithBotIfHeIsNotInWhitelist(): void
    {
        $bot = $this->bot;
        $bot->middleware(BotAccessMiddleware::class);

        $bot->onText('test', null);

        $bot->hearText('test')->reply()->assertReplyMessage([
            'text' => __('telegram.middleware.access_denied'),
        ]);
    }

    public function testUserCanInteractWithBotIfHeIsWhitelist(): void
    {
        // Using loop here instead of dataProvider because it seems that if config() is used
        // inside dataProvider, test that uses it cannot be found
        $whitelist = explode(',', (string) config('nutgram.whitelist', ''));

        $bot = $this->bot;
        $bot->middleware(BotAccessMiddleware::class);

        $bot->onText('test', function (Nutgram $bot) {
            $bot->sendMessage('test');
        });

        foreach ($whitelist as $id) {
            $bot->hearMessage($this->createFakeTextMessageUpdateData('test', ['id' => $id]))
                ->reply()
                ->assertReplyMessage(['text' => 'test']);
        }
    }
}
