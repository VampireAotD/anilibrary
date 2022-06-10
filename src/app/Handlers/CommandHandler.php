<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanCheckIfUserHasAccessForBot;
use App\Jobs\PickRandomAnimeJob;
use App\Jobs\ProvideAnimeListJob;
use Illuminate\Support\Facades\Cache;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\CommandEnum;
use App\Enums\AnimeHandlerEnum;

/**
 * Class CommandHandler
 * @package App\Handlers
 */
class CommandHandler extends UpdateHandler
{
    use CanCheckIfUserHasAccessForBot;

    private array $commands;

    public function __construct(TeleBot $bot, Update $update)
    {
        parent::__construct($bot, $update);

        $this->commands = CommandEnum::values();
    }

    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        return isset($update->message->text);
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $message = $this->update->message;
        $telegramId = $message->from->id;

        if ($this->userHasAccess($telegramId)) {
            if (in_array($message->text, $this->commands, true)) {
                UserHistory::addLastActiveTime($telegramId);
                UserHistory::addExecutedCommand($telegramId, $message->text);
            }

            match ($message->text) {
                CommandEnum::ADD_NEW_TITLE->value,
                CommandEnum::ADD_NEW_TITLE_COMMAND->value =>
                $this->sendMessage([
                    'text' => AnimeHandlerEnum::PROVIDE_URL->value,
                ]),

                CommandEnum::RANDOM_ANIME->value,
                CommandEnum::RANDOM_ANIME_COMMAND->value =>
                PickRandomAnimeJob::dispatch($telegramId),

                CommandEnum::ANIME_LIST->value,
                CommandEnum::ANIME_LIST_COMMAND->value =>
                ProvideAnimeListJob::dispatch($telegramId),

                default => UserHistory::addLastActiveTime($telegramId)
            };
        }
    }
}
