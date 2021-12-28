<?php

namespace App\Handlers;

use App\Handlers\Traits\CanCheckIfUserHasAccessForBot;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\KeyboardEnum;
use App\Enums\AnimeHandlerEnum;

/**
 * Class CommandHandler
 * @package App\Handlers
 */
class CommandHandler extends UpdateHandler
{
    use CanCheckIfUserHasAccessForBot;

    public static array $executedCommands = [];

    private array $commands;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->commands = KeyboardEnum::values();
    }

    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->message);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $message = $this->update->message;

        if ($this->userHasAccess($message->from->id)) {
            if (isset($message->text)) {
                if (in_array($message->text, $this->commands, true)) {
                    self::$executedCommands[] = $message->text;
                }

                match ($message->text) {
                    KeyboardEnum::ADD_NEW_TITLE->value => $this->sendMessage([
                        'text' => AnimeHandlerEnum::PROVIDE_URL->value,
                    ]),
                    default => ''
                };
            }
        }
    }
}
