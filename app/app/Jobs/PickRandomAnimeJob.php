<?php

namespace App\Jobs;

use App\Enums\QueueEnum;
use App\Handlers\History\UserHistory;
use App\Handlers\Traits\CanConvertAnimeToCaption;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

/**
 * Class PickRandomAnimeJob
 * @package App\Jobs
 */
class PickRandomAnimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CanConvertAnimeToCaption;

    private AnimeRepositoryInterface $animeRepository;

    private const EMPTY_ANIME_DATABASE = "К сожалению сейчас бот не содержит информацию ни об одном аниме \xF0\x9F\x98\xAD";

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private int $userId)
    {
        $this->animeRepository = app(AnimeRepositoryInterface::class);

        $this->onQueue(QueueEnum::PICK_RANDOM_ANIME_QUEUE->value);
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            UserHistory::addLastActiveTime($this->userId);

            $randomAnime = $this->animeRepository->findRandomAnime();

            if (!$randomAnime) {
                TeleBot::sendMessage([
                    'text' => self::EMPTY_ANIME_DATABASE,
                    'chat_id' => $this->userId,
                ]);

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
