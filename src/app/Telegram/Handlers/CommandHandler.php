<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\CommandEnum;
use App\Enums\Telegram\Handlers\AddAnimeHandlerEnum;
use App\Jobs\Telegram\PickRandomAnimeJob;
use App\Jobs\Telegram\ProvideAnimeListJob;
use GuzzleHttp\Promise\PromiseInterface;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class CommandHandler
 * @package App\Telegram\Handlers
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
     * @return PromiseInterface|void|Message
     */
    public function handle()
    {
        $message    = $this->update->message;
        $telegramId = $message->chat->id;

        switch ($message->text) {
            case CommandEnum::ADD_ANIME_BUTTON->value:
            case CommandEnum::ADD_NEW_TITLE_COMMAND->value:
                return $this->sendMessage(
                    [
                        'text' => AddAnimeHandlerEnum::PROVIDE_URL->value,
                    ]
                );
            case CommandEnum::RANDOM_ANIME_BUTTON->value :
            case CommandEnum::RANDOM_ANIME_COMMAND->value :
                PickRandomAnimeJob::dispatch($telegramId);
                return;
            case CommandEnum::ANIME_LIST_BUTTON->value:
            case CommandEnum::ANIME_LIST_COMMAND->value:
                ProvideAnimeListJob::dispatch($telegramId);
                return;
            default:
                return;
        }
    }
}
