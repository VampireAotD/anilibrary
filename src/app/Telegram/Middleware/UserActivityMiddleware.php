<?php

declare(strict_types=1);

namespace App\Telegram\Middleware;

use App\Enums\Telegram\Actions\CommandEnum;
use App\Enums\Telegram\Buttons\CommandButtonEnum;
use App\Facades\Telegram\State\UserStateFacade;
use SergiX44\Nutgram\Nutgram;

final class UserActivityMiddleware
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $userId            = $bot->userId();
        $message           = $bot->message()?->text;
        $supportedCommands = [...CommandEnum::values(), ...CommandButtonEnum::values()];

        UserStateFacade::setLastActiveAt($userId);

        if (!$userId || !$message) {
            $next($bot);
            return;
        }

        if (in_array($message, $supportedCommands, true)) {
            UserStateFacade::addExecutedCommand($userId, $message);
        }

        $next($bot);
    }
}
