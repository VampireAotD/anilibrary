<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\CallbackData\CreateCallbackDataDTO;
use App\DTO\UseCase\Anime\ScrapedDataDTO;
use App\Enums\QueueEnum;
use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Services\Telegram\CallbackDataService;
use App\Telegram\History\UserHistory;
use App\UseCase\AnimeUseCase;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;
use WeStacks\TeleBot\Objects\Message;

/**
 * Class AddNewAnimeJob
 * @package App\Jobs\Telegram
 */
class AddNewAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Message $message
     */
    public function __construct(private readonly Message $message)
    {
        $this->onQueue(QueueEnum::ADD_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * @param Client              $client
     * @param AnimeUseCase        $animeUseCase
     * @param CallbackDataService $callbackQueryService
     * @return void
     */
    public function handle(Client $client, AnimeUseCase $animeUseCase, CallbackDataService $callbackQueryService): void
    {
        $message = $this->message;
        $chatId  = $message->chat->id;

        UserHistory::addLastActiveTime($chatId);

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
                ['url' => $message->text, 'telegramId' => $chatId],
                json_decode($response->getBody()->getContents(), true)
            );
            $anime = $animeUseCase->createAnime(new ScrapedDataDTO(...$data));

            TeleBot::sendMessage(
                [
                    'text'         => AnimeHandlerEnum::PARSE_HAS_ENDED->value,
                    'chat_id'      => $chatId,
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text'          => AnimeHandlerEnum::WATCH_RECENTLY_ADDED_ANIME->value,
                                    'callback_data' => $callbackQueryService->create(
                                        new CreateCallbackDataDTO(CallbackQueryEnum::CHECK_ADDED_ANIME, $anime->id)
                                    ),
                                ],
                            ],
                        ],
                    ],
                ]
            );

            UserHistory::clearUserExecutedCommandsHistory($chatId);
        } catch (GuzzleException | InvalidScrapedDataException | Exception $exception) {
            TeleBot::sendMessage(
                [
                    'chat_id' => $chatId,
                    'text'    => AnimeHandlerEnum::PARSE_FAILED->value,
                ]
            );

            logger()->channel('single')->warning(
                $exception->getMessage(),
                ['exceptionTrace' => $exception->getTraceAsString()]
            );
        }
    }
}
