<?php

namespace App\Handlers;

use App\Console\Commands\Telegram\RandomAnimeCommand;
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
    public static array $executedCommands = [];

    private array $commands;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->commands = $this->resolveCommands();
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
        $message = $this->update->message->text;

        if(in_array($message, $this->commands, true)){
            self::$executedCommands[] = $message;
        }

        match ($message) {
            KeyboardEnum::ADD_NEW_TITLE->value => $this->sendMessage([
                'text' => AnimeHandlerEnum::PROVIDE_URL->value,
            ]),
            default => ''
        };
    }

    /**
     * @return array
     */
    private function resolveCommands(): array
    {
        return array_map(fn(KeyboardEnum $keyboardEnum) => $keyboardEnum->value, KeyboardEnum::cases());
    }
}
