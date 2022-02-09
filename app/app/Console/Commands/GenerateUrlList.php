<?php

namespace App\Console\Commands;

use App\Models\Anime;
use App\Repositories\Contracts\Anime\AnimeRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class GenerateUrlList
 * @package App\Console\Commands
 */
class GenerateUrlList extends Command
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
        $pathToFile = storage_path('lists/urlList.csv');

        if (File::exists($pathToFile)) {
            File::delete($pathToFile);
        }

        foreach ($this->animeListGenerator() as $anime) {
            /**
             * @var $anime Anime
             */
            File::append($pathToFile, $anime->url . PHP_EOL);
        }

        return Command::SUCCESS;
    }

    /**
     * @return \Generator
     */
    private function animeListGenerator(): \Generator
    {
        $animeList = $this->animeRepository->getAll(
            ['url'],
            []
        );

        foreach ($animeList as $anime) {
            yield $anime;
        }
    }
}
