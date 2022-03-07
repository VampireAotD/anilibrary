<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
    protected $signature = 'url-list:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates url list';

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
        $pathToFile = storage_path('lists/animeList.json');

        if (File::exists($pathToFile)) {
            File::delete($pathToFile);
        }

        $animeList = $this->animeRepository->getAll(
            ['title', 'url', 'rating', 'episodes',],
            []
        );

        $pathToFile = storage_path('lists/animeList.json');

        File::put($pathToFile, $animeList->toJson(JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
