<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Facades\Telegram\History\UserHistory;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Objects\ChatMemberBanned;
use WeStacks\TeleBot\Objects\ChatMemberLeft;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class UserStatusMiddleware
 * @package App\Telegram\Middlewares
 */
class UserStatusMiddleware
{
    /**
     * @param TeleBot $bot
     * @param Update  $update
     * @param         $next
     * @return PromiseInterface|mixed|Message
     */
    public function __invoke(TeleBot $bot, Update $update, $next): mixed
    {
        if (!isset($update->my_chat_member)) {
            return $next();
        }

        $chatMember = $update->my_chat_member->new_chat_member;

        switch (true) {
            case $chatMember instanceof ChatMemberLeft:
            case $chatMember instanceof ChatMemberBanned:
                UserHistory::clearUserExecutedCommandsHistory($update->chat()->id);
                return fn() => null;
            default:
                return $next();
        }
    }
}
