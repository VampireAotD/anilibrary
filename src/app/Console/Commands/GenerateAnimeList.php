<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\AnimeListMail;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

/**
 * Class GenerateAnimeList
 * @package App\Console\Commands
 */
class GenerateAnimeList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime-list:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate anime list';

    /**
     * Create a new command instance.
     */
    public function __construct(private AnimeRepositoryInterface $animeRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $animeList = $this->animeRepository->getAll(
            [
                'title',
                'url',
                'rating',
                'episodes',
            ],
            []
        );

        File::put(config('filesystems.animeListPath'), $animeList->toJson(JSON_PRETTY_PRINT));

        Mail::to(config('admin.email'))
            ->queue(new AnimeListMail());

        return Command::SUCCESS;
    }
}