<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CommandEnum;
use App\Enums\Validation\SupportedUrlEnum;
use App\Jobs\Telegram\AddNewAnimeJob;
use App\Telegram\History\UserHistory;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WeStacks\TeleBot\Handlers\UpdateHandler;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class AddNewAnimeHandler
 * @package App\Telegram\Handlers
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
                UserHistory::userLastExecutedCommand($this->update->message->chat->id),
                $allowedCommands,
                true
            );
    }

    /**
     * @return PromiseInterface|void|Message
     */
    public function handle()
    {
        $message = $this->update->message;
        $chatId  = $message->chat->id;

        if (!in_array(
            $message->text,
            [CommandEnum::ADD_NEW_TITLE->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value],
            true
        )) {
            try {
                $this->validUrl($message->text);
            } catch (ValidationException $exception) {
                return $this->sendMessage(
                    [
                        'text'    => $exception->getMessage(),
                        'chat_id' => $chatId,
                    ]
                );
            }

            AddNewAnimeJob::dispatch($message);

            return $this->sendMessage(
                [
                    'text'    => AnimeHandlerEnum::PARSE_STARTED->value,
                    'chat_id' => $chatId,
                ]
            );
        }
    }

    /**
     * @param string $url
     * @return array
     */
    private function validUrl(string $url): array
    {
        return Validator::validate(
            ['url' => $url],
            ['url' => 'required|supported_url'],
            ['required' => SupportedUrlEnum::INVALID_URL->value]
        );
    }
}
