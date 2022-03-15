<?php

namespace App\Jobs;

use App\Dto\Handlers\CallbackDataDto;
use App\Enums\AnimeHandlerEnum;
use App\Enums\CallbackQueryEnum;
use App\Enums\CommandEnum;
use App\Enums\QueueEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Factories\ParserFactory;
use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanCreateCallbackData;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use WeStacks\TeleBot\Laravel\TeleBot;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class AddNewAnimeJob
 * @package App\Jobs
 */
class AddNewAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CanCreateCallbackData;

    private ParserFactory $parserFactory;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Message $message)
    {
        $this->parserFactory = app(ParserFactory::class);

        $this->onQueue(QueueEnum::ADD_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $message = $this->message;
        $telegramId = $message->from->id;
        $excludeMessages = [CommandEnum::ADD_NEW_TITLE->value, CommandEnum::ADD_NEW_TITLE_COMMAND->value];

        try {
            UserHistory::addLastActiveTime($telegramId);
            if (!in_array($message->text, $excludeMessages, true)) {
                $data = [
                    'url' => $message->text
                ];

                if ($validated = $this->makeUrlValidator($data)) {
                    TeleBot::sendMessage([
                        'text' => AnimeHandlerEnum::STARTED_PARSE_MESSAGE->value,
                        'chat_id' => $telegramId,
                    ]);

                    $anime = $this->parserFactory->getParser($validated['url'])
                        ->parse($validated['url'], $telegramId);

                    if ($anime) {
                        $animeId = $anime->id instanceof LazyUuidFromString ? $anime->id->toString() : $anime->id;

                        TeleBot::sendMessage([
                            'text' => AnimeHandlerEnum::PARSE_HAS_ENDED->value,
                            'reply_markup' => [
                                'inline_keyboard' => [
                                    [
                                        [
                                            'text' => AnimeHandlerEnum::WATCH_RECENTLY_ADDED_ANIME->value,
                                            'callback_data' => $this->createCallbackData(
                                                CallbackQueryEnum::CHECK_ADDED_ANIME,
                                                new CallbackDataDto($animeId)
                                            ),
                                        ]
                                    ]
                                ]
                            ],
                            'chat_id' => $telegramId,
                        ]);

                        UserHistory::clearUserExecutedCommandsHistory($telegramId);
                    }
                }
            }
        } catch (ValidationException $exception) {
            TeleBot::sendMessage([
                'text' => $exception->validator->errors()->first(),
                'chat_id' => $telegramId,
            ]);
        } catch (UndefinedAnimeParserException | InvalidUrlException | GuzzleException $exception) {
            TeleBot::sendMessage([
                'text' => $exception->getMessage(),
                'chat_id' => $telegramId,
            ]);
        } catch (\Exception $exception) {
            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'exceptionTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function makeUrlValidator(array $data): array
    {
        return Validator::make($data, [
            'url' => 'required|url',
        ], [
            'url' => AnimeHandlerEnum::INVALID_URL->value
        ])->validate();
    }
}
