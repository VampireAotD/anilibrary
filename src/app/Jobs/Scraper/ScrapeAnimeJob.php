<?php

declare(strict_types=1);

namespace App\Jobs\Scraper;

use App\DTO\Events\Pusher\ScrapeResultDTO;
use App\Enums\Events\Pusher\ScrapeResultTypeEnum;
use App\Enums\QueueEnum;
use App\Events\Pusher\ScrapeResultEvent;
use App\UseCase\Scraper\AnimeUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use Throwable;

class ScrapeAnimeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly string $userId, public readonly string $url)
    {
        $this->onConnection('redis')->onQueue(QueueEnum::SCRAPE_ANIME_QUEUE->value);
    }

    /**
     * Execute the job.
     */
    public function handle(AnimeUseCase $animeUseCase): void
    {
        try {
            $anime = $animeUseCase->scrapeAndCreateAnime($this->url);
            ScrapeResultEvent::broadcast(
                new ScrapeResultDTO(
                    $this->userId,
                    ScrapeResultTypeEnum::SUCCESS,
                    $anime->id
                )
            );
        } catch (RequestException | ValidationException | Throwable $e) {
            ScrapeResultEvent::broadcast(
                new ScrapeResultDTO(
                    $this->userId,
                    ScrapeResultTypeEnum::ERROR,
                    $e->getMessage()
                )
            );
        }
    }
}