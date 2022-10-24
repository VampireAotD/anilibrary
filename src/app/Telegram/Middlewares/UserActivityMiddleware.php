<?php
declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Enums\Telegram\CommandEnum;
use App\Telegram\History\UserHistory;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class UserActivityMiddleware
{
    /**
     * @param TeleBot $bot
     * @param Update  $update
     * @param         $next
     * @return PromiseInterface|mixed|Message
     */
    public function __invoke(TeleBot $bot, Update $update, $next): mixed
    {
        $supportedCommands = CommandEnum::values();
        $userId            = $update->chat()->id;
        $message           = $update->message;

        UserHistory::addLastActiveTime($userId);

        if (isset($message->text) && in_array($message->text, $supportedCommands, true)) {
            UserHistory::addExecutedCommand($userId, $message->text);
        }

        return $next();
    }
}
