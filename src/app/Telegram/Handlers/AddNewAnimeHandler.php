<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Jobs\AddNewAnimeJob;
use App\Rules\SupportedUrl;
use App\Telegram\Handlers\History\UserHistory;
use Illuminate\Support\Facades\Validator;
use WeStacks\TeleBot\Handlers\UpdateHandler;

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

    public function handle()
    {
        $message = $this->update->message;

        if (!in_array($message->text, [CommandEnum::ADD_NEW_TITLE->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value], true)) {
            if (!$this->validUrl($message->text)) {
                return $this->sendMessage([
                    'text'    => AnimeHandlerEnum::INVALID_URL->value,
                    'chat_id' => $message->from->id,
                ]);
            }

            AddNewAnimeJob::dispatch($message);

            return $this->sendMessage([
                'text'    => AnimeHandlerEnum::STARTED_PARSE_MESSAGE->value,
                'chat_id' => $message->from->id,
            ]);
        }
    }

    private function validUrl(string $url): bool
    {
        $validator = Validator::make(['url' => $url], [
            'url' => [
                'required',
                'url',
                new SupportedUrl(),
            ],
        ]);

        return !$validator->fails();
    }
}
