<?php

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
        return isset($update->message->text) && UserHistory::userLastExecutedCommand($update->message->from->id)
            === CommandEnum::ADD_NEW_TITLE->value;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $message = $this->update->message;

        AddNewAnimeJob::dispatch($message)->onConnection('redis');
    }
}
