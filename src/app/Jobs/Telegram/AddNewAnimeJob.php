<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\CallbackData\CreateCallbackDataDTO;
use App\Enums\QueueEnum;
use App\Enums\Telegram\AnimeHandlerEnum;
use App\Enums\Telegram\CallbackQueryEnum;
use App\Exceptions\UseCase\Anime\InvalidScrapedDataException;
use App\Facades\Telegram\History\UserHistory;
use App\Services\Telegram\CallbackDataService;
use App\UseCase\AnimeUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
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
        $this->onQueue(QueueEnum::ADD_ANIME_QUEUE->value)->onConnection('redis');
    }

    /**
     * @param AnimeUseCase        $animeUseCase
     * @param CallbackDataService $callbackQueryService
     * @return void
     */
    public function handle(AnimeUseCase $animeUseCase, CallbackDataService $callbackQueryService): void
    {
        $message = $this->message;
        $chatId  = $message->chat->id;

        UserHistory::addLastActiveTime($chatId);

        try {
            if ($anime = $animeUseCase->findByUrl($message->text)) {
                $callbackData = $callbackQueryService->create(
                    new CreateCallbackDataDTO(CallbackQueryEnum::CHECK_ADDED_ANIME, $anime->id)
                );

                $this->sendScrapedStatusMessage($chatId, $callbackData);
                return;
            }

            $dto   = $animeUseCase->sendScrapeRequest($message->text, $chatId);
            $anime = $animeUseCase->createAnime($dto);

            $callbackData = $callbackQueryService->create(
                new CreateCallbackDataDTO(CallbackQueryEnum::CHECK_ADDED_ANIME, $anime->id)
            );
            $this->sendScrapedStatusMessage($chatId, $callbackData);

            UserHistory::clearUserExecutedCommandsHistory($chatId);
        } catch (RequestException | InvalidScrapedDataException | Throwable $exception) {
            TeleBot::sendMessage(
                [
                    'chat_id' => $chatId,
                    'text'    => AnimeHandlerEnum::PARSE_FAILED->value,
                ]
            );

            logger()->channel('single')->warning(
                $exception->getMessage(),
                [
                    'url'              => $message->text,
                    'exceptionMessage' => $exception->getMessage(),
                    'exceptionTrace'   => $exception->getTraceAsString(),
                ]
            );
        }
    }

    private function sendScrapedStatusMessage(int $chatId, string $callbackData): void
    {
        TeleBot::sendMessage(
            [
                'text'         => AnimeHandlerEnum::PARSE_HAS_ENDED->value,
                'chat_id'      => $chatId,
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            [
                                'text'          => AnimeHandlerEnum::WATCH_RECENTLY_ADDED_ANIME->value,
                                'callback_data' => $callbackData,
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
