<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Facades\Telegram\State\UserStateFacade;
use WeStacks\TeleBot\Handlers\UpdateHandler;

/**
 * Class TextMessageUpdateHandler
 * @package App\Telegram\Handlers
 */
abstract class TextMessageUpdateHandler extends UpdateHandler
{
    protected array $allowedMessages = [];

    protected $async = true;

    public function trigger(): bool
    {
        return isset($this->update->message->text) && $this->commandsWereExecuted();
    }

    protected function commandsWereExecuted(): bool
    {
        return in_array(
            UserStateFacade::getLastExecutedCommand($this->update->chat()->id),
            $this->allowedMessages,
            true
        );
    }
}