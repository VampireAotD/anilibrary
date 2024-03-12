<?php

declare(strict_types=1);

namespace App\Telegram\Middleware;

use App\Facades\Telegram\State\UserStateFacade;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMemberBanned;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMemberLeft;

final class UserStatusMiddleware
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $chatMemberUpdate = $bot->update()?->my_chat_member;

        if (!$chatMemberUpdate) {
            $next($bot);
            return;
        }

        $chatMember = $chatMemberUpdate->new_chat_member;

        switch (true) {
            case $chatMember instanceof ChatMemberLeft:
            case $chatMember instanceof ChatMemberBanned:
                UserStateFacade::resetExecutedCommandsList($chatMemberUpdate->chat->id);
                $next($bot);
                return;
            default:
                $next($bot);
        }
    }
}
