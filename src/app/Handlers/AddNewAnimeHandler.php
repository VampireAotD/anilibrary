<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Jobs\AddNewAnimeJob;
use WeStacks\TeleBot\Interfaces\UpdateHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use App\Enums\CommandEnum;

/**
 * Class AddNewAnimeHandler
 * @package App\Handlers
 */
class AddNewAnimeHandler extends UpdateHandler
{
    /**
     * @param Update $update
     * @param TeleBot $bot
     * @return bool
     */
    public static function trigger(Update $update, TeleBot $bot): bool
    {
        $allowedCommands = [CommandEnum::ADD_NEW_TITLE->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value];

        return isset($update->message->text)
            && in_array(
                UserHistory::userLastExecutedCommand($update->message->from->id),
                $allowedCommands,
                true
            );
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $message = $this->update->message;

        AddNewAnimeJob::dispatch($message);
    }
}
