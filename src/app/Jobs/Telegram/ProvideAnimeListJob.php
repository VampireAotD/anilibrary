<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\Telegram\CreateAnimeCaptionDTO;
use App\Enums\QueueEnum;
use App\Repositories\Contracts\AnimeRepositoryInterface;
use App\Services\Telegram\CaptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use WeStacks\TeleBot\Laravel\TeleBot;

/**
 * Class ProvideAnimeListJob
 * @package App\Jobs\Telegram
 */
class ProvideAnimeListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $telegramId,
        private readonly int $page = 1,
    ) {
        $this->onQueue(QueueEnum::ANIME_LIST_QUEUE->value)->onConnection('redis');
    }

    /**
     * @param AnimeRepositoryInterface $animeRepository
     * @param CaptionService           $captionService
     * @return void
     */
    public function handle(AnimeRepositoryInterface $animeRepository, CaptionService $captionService): void
    {
        $list = $animeRepository->paginate(currentPage: $this->page);

        $caption = $captionService->create(new CreateAnimeCaptionDTO($list->first(), $this->telegramId, $list));

        TeleBot::sendPhoto($caption);
    }
}
