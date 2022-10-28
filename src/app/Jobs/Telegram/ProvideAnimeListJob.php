<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\Enums\QueueEnum;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Telegram\Handlers\Traits\CanConvertAnimeToCaption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

/**
 * Class ProvideAnimeListJob
 *
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
        private int $telegramId,
        private int $page = 1,
    ) {
        $this->animeRepository = app(AnimeRepositoryInterface::class);

        $this->resolveBindings();
        $this->onQueue(QueueEnum::ANIME_LIST_QUEUE->value);
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
