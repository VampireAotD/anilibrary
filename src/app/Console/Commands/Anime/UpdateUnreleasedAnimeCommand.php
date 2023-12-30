<?php

declare(strict_types=1);

namespace App\Console\Commands\Anime;

use App\Mail\Anime\FailedUnreleasedAnimeMail;
use App\Models\Anime;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AnimeService;
use App\UseCase\Scraper\ScraperUseCase;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateUnreleasedAnimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime:update-unreleased';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information for unreleased anime';

    public function __construct(
        private readonly AnimeService            $animeService,
        private readonly ScraperUseCase          $scraperUseCase,
        private readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $failedList = [];

        $this->animeService->unreleased()->each(function (Anime $anime) use (&$failedList) {
            try {
                $this->scraperUseCase->scrapeAndCreateAnime($anime->urls->first()->url);
            } catch (RequestException | ValidationException | Throwable $e) {
                $failedList[$anime->id] = $e->getMessage();
            }
        });

        $owner = $this->userRepository->findOwner();

        if ($owner && $failedList) {
            Mail::to($owner->email)->queue(new FailedUnreleasedAnimeMail($failedList));
            $this->warn('Failed to update some anime, mail is queued');
        }

        $this->info('All anime has been updated!');

        return Command::SUCCESS;
    }
}
