<?php

namespace App\Jobs;

use App\Enums\QueueEnum;
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
 * Class ProvideAnimeListJob
 * @package App\Jobs
 */
class ProvideAnimeListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CanConvertAnimeToCaption;

    private AnimeRepositoryInterface $animeRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int  $telegramId,
        private ?int $page = 1
    )
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
        $list = $this->animeRepository->paginate(currentPage: $this->page);

        $caption = $this->convertToCaption($list->first(), $this->telegramId, $list);

        TeleBot::sendPhoto($caption);
    }
}
