<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use App\Enums\Telegram\Middlewares\BotAccessEnum;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class BotAccessMiddleware
{
    /**
     * @param TeleBot $bot
     * @param Update  $update
     * @param         $next
     * @return PromiseInterface|mixed|Message
     */
    public function __invoke(TeleBot $bot, Update $update, $next): mixed
    {
        $userId = $update->chat()->id;

        if ($userId !== config('admin.id')) {
            return $bot->sendMessage(
                [
                    'chat_id' => $userId,
                    'text'    => BotAccessEnum::ACCESS_DENIED_MESSAGE->value,
                ]
            );
        }

        return $next();
    }
}
