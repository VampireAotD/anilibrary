<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTO\Handlers\CallbackDataDTO;
use App\Enums\QueueEnum;
use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Factories\ParserFactory;
use App\Telegram\Handlers\History\UserHistory;
use App\Telegram\Handlers\Traits\CanCreateCallbackData;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use WeStacks\TeleBot\Laravel\TeleBot;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class AddNewAnimeJob
 *
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
    public function __construct(private readonly Message $message)
    {
        $this->parserFactory = app(ParserFactory::class);

        $this->resolveBindings();
        $this->onQueue(QueueEnum::ADD_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $message    = $this->message;
        $telegramId = $message->from->id;

        try {
            UserHistory::addLastActiveTime($telegramId);

            $anime = $this->parserFactory->getParser($message->text)
                                         ->parse($message->text, $telegramId);

            if ($anime) {
                $animeId = $anime->id instanceof LazyUuidFromString ? $anime->id->toString() : $anime->id;

                TeleBot::sendMessage([
                    'text'         => AnimeHandlerEnum::PARSE_HAS_ENDED->value,
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text'          => AnimeHandlerEnum::WATCH_RECENTLY_ADDED_ANIME->value,
                                    'callback_data' => $this->createCallbackData(
                                        CallbackQueryEnum::CHECK_ADDED_ANIME,
                                        new CallbackDataDTO((string) $animeId)
                                    ),
                                ],
                            ],
                        ],
                    ],
                    'chat_id'      => $telegramId,
                ]);

                UserHistory::clearUserExecutedCommandsHistory($telegramId);
            }
        } catch (\Exception $exception) {
            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'exceptionTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
