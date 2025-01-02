<?php

declare(strict_types=1);

namespace App\Telegram\Middleware;

use SergiX44\Nutgram\Nutgram;

final class BotAccessMiddleware
{
    public function __invoke(Nutgram $bot, mixed $next): void
    {
        $whitelist = explode(',', config('nutgram.whitelist', ''));

        if (!in_array((string) $bot->userId(), $whitelist, true)) {
            $bot->sendMessage(__('telegram.middleware.access_denied'));
            return;
        }

        $next($bot);
    }
}
