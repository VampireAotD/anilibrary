<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Exceptions\Parsers\InvalidUrlException;
use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Factories\ParserFactory;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class ParseAnimeList
 * @package App\Console\Commands
 */
class ParseAnimeList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime-list:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse all titles from list';

    /**
     * Create a new command instance.
     */
    public function __construct(private ParserFactory $parserFactory)
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
        $pathToFile = config('filesystems.animeListPath');

        if (!File::exists($pathToFile)) {
            $this->line('Anime list not found', 'warning');

            return Command::FAILURE;
        }

        $animeList = json_decode(File::get($pathToFile));

        $bar = $this->output->createProgressBar(count($animeList));

        foreach ($animeList as $anime) {
            $link = $anime->url;

            try {
                $this->parserFactory->getParser($link)->parse($link);
                $bar->advance();
            } catch (GuzzleException | InvalidUrlException | UndefinedAnimeParserException $e) {
                logger()->info(
                    $link,
                    [
                        'exceptionMessage' => $e->getMessage(),
                        'exceptionTrace'   => $e->getTraceAsString(),
                    ]
                );
            }
        }

        return Command::SUCCESS;
    }
}
