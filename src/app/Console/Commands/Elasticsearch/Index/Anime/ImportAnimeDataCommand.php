<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use App\Filters\ColumnFilter;
use App\Filters\RelationFilter;
use App\Models\Anime;
use App\Services\AnimeService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ImportAnimeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:import-anime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all anime data into Elasticsearch';

    /**
     * Execute the console command.
     * @psalm-suppress InvalidArgument
     */
    public function handle(AnimeService $animeService, Client $client): int
    {
        $this->info('Importing all anime into Elasticsearch index...');

        $animeList = $animeService->all([
            new ColumnFilter(['id', 'title', 'type', 'status', 'rating', 'year', 'episodes']),
            new RelationFilter(['synonyms:anime_id,name', 'genres:id,name', 'voiceActing:id,name']),
        ]);

        $bar = $this->output->createProgressBar($animeList->count());

        /** @psalm-suppress InvalidTemplateParam */
        $animeList->chunk(100)->each(function (Collection $collection) use ($client, $bar) {
            $batch = ['body' => []];

            /** @var LazyCollection<int, Anime> $collection */
            $collection->each(function (Anime $anime) use (&$batch, $bar) {
                $batch['body'][] = [
                    'index' => [
                        '_index' => IndexEnum::ANIME_INDEX->value,
                        '_id'    => $anime->id,
                    ],
                ];

                $batch['body'][] = $anime->toArray();

                $bar->advance();
            });

            try {
                $client->bulk($batch);
            } catch (ClientResponseException | ServerResponseException $e) {
                Log::error('Sync anime data command', [
                    'exception_trace'   => $e->getTraceAsString(),
                    'exception_message' => $e->getMessage(),
                ]);
            }
        });

        $bar->finish();
        $this->newLine()->info('All anime data has been synced');

        return self::SUCCESS;
    }
}
