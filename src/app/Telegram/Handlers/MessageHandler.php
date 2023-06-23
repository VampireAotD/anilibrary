<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\Commands\CommandEnum;
use App\Enums\Telegram\Handlers\MessageHandlerEnum;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class MessageHandler
 * @package App\Telegram\Handlers
 */
final class MessageHandler extends UpdateHandler
{
    public function trigger(): bool
    {
        return isset($this->update->message->text);
    }

    /**
     * @return PromiseInterface|Message|void
     */
    public function handle()
    {
        $message = $this->update->message;

        switch ($message->text) {
            case CommandEnum::ADD_ANIME_BUTTON->value:
            case CommandEnum::ADD_NEW_TITLE_COMMAND->value:
                return $this->sendMessage(
                    [
                        'text' => MessageHandlerEnum::PROVIDE_URL->value,
                    ]
                );
            default:
        }
    }
}
