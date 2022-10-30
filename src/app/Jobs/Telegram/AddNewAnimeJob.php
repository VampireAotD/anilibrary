<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Handlers\CallbackDataDTO;
use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Enums\QueueEnum;
use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Telegram\Handlers\Traits\CanCreateCallbackData;
use App\Telegram\History\UserHistory;
use App\UseCase\AnimeUseCase;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
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
 * @package App\Jobs\Telegram
 */
class AddNewAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CanCreateCallbackData;

    /**
     * @param Message $message
     */
    public function __construct(private readonly Message $message)
    {
        $this->resolveBindings();
        $this->onQueue(QueueEnum::ADD_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @param Client       $client
     * @param AnimeUseCase $animeUseCase
     * @return void
     * @throws GuzzleException
     */
    public function handle(Client $client, AnimeUseCase $animeUseCase): void
    {
        $message    = $this->message;
        $telegramId = $message->chat->id;

        UserHistory::addLastActiveTime($telegramId);

        try {
            $response = $client->post(
                sprintf('%s/api/v1/anime/parse', config('scraper.url')),
                [
                    RequestOptions::JSON => [
                        'url' => $message->text,
                    ],
                ]
            );

            $data  = array_merge(
                ['url' => $message->text, 'telegramId' => $telegramId],
                json_decode($response->getBody()->getContents(), true)
            );
            $anime = $animeUseCase->createAnime(new ScrapedDataDTO(...$data));

            $animeId = $anime->id instanceof LazyUuidFromString ? $anime->id->toString() : $anime->id;

            TeleBot::sendMessage(
                [
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
                ]
            );

            UserHistory::clearUserExecutedCommandsHistory($telegramId);
        } catch (BadResponseException $exception) {
            TeleBot::sendMessage(
                [
                    'chat_id' => $message->chat->id,
                    'text'    => $exception->getMessage(),
                ]
            );
        } catch (Exception $exception) {
            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'exceptionTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
