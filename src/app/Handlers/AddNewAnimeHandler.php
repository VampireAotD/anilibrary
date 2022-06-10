<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Handlers\History\UserHistory;
use App\Jobs\AddNewAnimeJob;
use WeStacks\TeleBot\Handlers\UpdateHandler;
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
     * @return bool
     */
    public function trigger(): bool
    {
        $allowedCommands = [CommandEnum::ADD_NEW_TITLE->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value];

        return isset($this->update->message->text)
            && in_array(
                UserHistory::userLastExecutedCommand($this->update->message->from->id),
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
