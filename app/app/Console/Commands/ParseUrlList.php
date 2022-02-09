<?php

namespace App\Console\Commands;

use App\Exceptions\Parsers\InvalidUrlException;
use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Factories\ParserFactory;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class ParseUrlList
 * @package App\Console\Commands
 */
class ParseUrlList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url-list:parse';

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
        $pathToFile = storage_path('lists/urlList.csv');

        if (!File::exists($pathToFile)) {
            $this->line('Url list not found', 'warning');

            return Command::FAILURE;
        }

        $links = File::get($pathToFile);

        $links = explode(PHP_EOL, $links);

        $bar = $this->output->createProgressBar(count($links));

        foreach ($links as $link) {
            try {
                $this->parserFactory->getParser($link)->parse($link);
                $bar->advance();
            } catch (GuzzleException | InvalidUrlException | UndefinedAnimeParserException $e) {
                logger()->info($link, [
                    'exceptionMessage' => $e->getMessage(),
                    'exceptionTrace' => $e->getTraceAsString(),
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
