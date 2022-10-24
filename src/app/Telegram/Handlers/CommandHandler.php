<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\PickRandomAnimeJob;
use App\Jobs\ProvideAnimeListJob;
use App\Telegram\Handlers\History\UserHistory;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

/**
 * Class CommandHandler
 * @package App\Handlers
 */
class CommandHandler extends UpdateHandler
{
    private array $commands;

    /**
     * @param TeleBot $bot
     * @param Update  $update
     */
    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->commands = CommandEnum::values();
    }

    /**
     * @return bool
     */
    public function trigger(): bool
    {
        return isset($this->update->message->text);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $message    = $this->update->message;
        $telegramId = $message->from->id;

        if (in_array($message->text, $this->commands, true)) {
            UserHistory::addLastActiveTime($telegramId);
            UserHistory::addExecutedCommand($telegramId, $message->text);
        }

        match ($message->text) {
            CommandEnum::ADD_NEW_TITLE->value,
            CommandEnum::ADD_NEW_TITLE_COMMAND->value =>
            $this->sendMessage(
                [
                    'text' => AnimeHandlerEnum::PROVIDE_URL->value,
                ]
            ),

            CommandEnum::RANDOM_ANIME->value,
            CommandEnum::RANDOM_ANIME_COMMAND->value  =>
            PickRandomAnimeJob::dispatch($telegramId),

            CommandEnum::ANIME_LIST->value,
            CommandEnum::ANIME_LIST_COMMAND->value    =>
            ProvideAnimeListJob::dispatch($telegramId),

            default                                   => UserHistory::addLastActiveTime($telegramId)
        };
    }
}
