<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\Telegram\CreateAnimeCaptionDTO;
use App\Enums\QueueEnum;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\CaptionService;
use App\Telegram\History\UserHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

/**
 * Class PickRandomAnimeJob
 * @package App\Jobs\Telegram
 */
class PickRandomAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $chatId)
    {
        $this->onQueue(QueueEnum::PICK_RANDOM_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @param AnimeRepositoryInterface $animeRepository
     * @param CaptionService           $captionService
     * @return void
     */
    public function handle(AnimeRepositoryInterface $animeRepository, CaptionService $captionService): void
    {
        try {
            UserHistory::addLastActiveTime($this->chatId);

            $randomAnime = $animeRepository->findRandomAnime();

            if (!$randomAnime) {
                TeleBot::sendMessage(
                    [
                        'text'    => QueueEnum::EMPTY_ANIME_DATABASE->value,
                        'chat_id' => $this->chatId,
                    ]
                );

                UserHistory::clearUserExecutedCommandsHistory($this->chatId);
                return;
            }

            TeleBot::sendPhoto($captionService->create(new CreateAnimeCaptionDTO($randomAnime, $this->chatId)));

            UserHistory::clearUserExecutedCommandsHistory($this->chatId);
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
