<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\PickRandomAnimeJob;
use App\Jobs\ProvideAnimeListJob;
use App\Telegram\History\UserHistory;
use WeStacks\TeleBot\Handlers\UpdateHandler;

/**
 * Class CommandHandler
 * @package App\Handlers
 */
class CommandHandler extends UpdateHandler
{
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
