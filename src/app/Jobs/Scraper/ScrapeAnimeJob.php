<?php

declare(strict_types=1);

namespace App\Jobs\Scraper;

use App\DTO\Events\Scraper\ScrapeAnimeResultDTO;
use App\Enums\Events\Scraper\ScrapeResultTypeEnum;
use App\Enums\QueueEnum;
use App\Events\Scraper\ScrapeAnimeResultEvent;
use App\UseCase\Scraper\ScraperUseCase;
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
        $this->onConnection('redis')->onQueue(QueueEnum::SCRAPER_QUEUE->value);
    }

    /**
     * Execute the job.
     */
    public function handle(ScraperUseCase $scraperUseCase): void
    {
        try {
            $anime = $scraperUseCase->scrapeByUrl($this->url);
            ScrapeAnimeResultEvent::broadcast(
                new ScrapeAnimeResultDTO(
                    $this->userId,
                    ScrapeResultTypeEnum::SUCCESS,
                    $anime->id
                )
            );
        } catch (RequestException | ValidationException | Throwable $e) {
            ScrapeAnimeResultEvent::broadcast(
                new ScrapeAnimeResultDTO(
                    $this->userId,
                    ScrapeResultTypeEnum::ERROR,
                    $e->getMessage()
                )
            );
        }
    }
}
