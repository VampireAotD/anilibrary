<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\QueueEnum;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use App\Telegram\Handlers\Traits\CanConvertAnimeToCaption;
use App\Telegram\History\UserHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

/**
 * Class PickRandomAnimeJob
 *
 * @package App\Jobs
 */
class PickRandomAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CanConvertAnimeToCaption;

    /**
     * @param int $userId
     */
    public function __construct(private readonly int $userId)
    {
        $this->resolveBindings();
        $this->onQueue(QueueEnum::PICK_RANDOM_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @param AnimeRepositoryInterface $animeRepository
     * @return void
     */
    public function handle(AnimeRepositoryInterface $animeRepository): void
    {
        try {
            UserHistory::addLastActiveTime($this->userId);

            $randomAnime = $animeRepository->findRandomAnime();

            if (!$randomAnime) {
                TeleBot::sendMessage(
                    [
                        'text'    => QueueEnum::EMPTY_ANIME_DATABASE->value,
                        'chat_id' => $this->userId,
                    ]
                );

                UserHistory::clearUserExecutedCommandsHistory($this->userId);
                return;
            }

            TeleBot::sendPhoto($this->convertToCaption($randomAnime, $this->userId));

            UserHistory::clearUserExecutedCommandsHistory($this->userId);
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
