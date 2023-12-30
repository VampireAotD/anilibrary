<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use App\Filters\ColumnFilter;
use App\Filters\RelationFilter;
use App\Models\Anime;
use App\Services\AnimeService;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SyncAnimeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:sync-anime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all anime data into Elasticsearch';

    /**
     * Execute the console command.
     * @psalm-suppress InvalidArgument
     */
    public function handle(AnimeService $animeService, Client $client): int
    {
        $this->info('Trying to sync all anime into Elasticsearch index...');

        $animeList = $animeService->all([
            new ColumnFilter(['id', 'title', 'status', 'rating', 'episodes']),
            new RelationFilter(['synonyms:anime_id,synonym', 'genres:id,name', 'voiceActing:id,name']),
        ]);

        $bar = $this->output->createProgressBar($animeList->count());

        /** @psalm-suppress InvalidTemplateParam */
        $animeList->chunk(100)->each(function (Collection $collection) use ($client, $bar) {
            $batch = ['body' => []];

            /** @phpstan-ignore-next-line */
            $collection->each(function (Anime $anime) use (&$batch, $bar) {
                $batch['body'][] = [
                    'index' => [
                        '_index' => IndexEnum::ANIME_INDEX->value,
                    ],
                ];

                $batch['body'][] = $anime->toArray();

                $bar->advance();
            });

            $client->bulk($batch);
        });

        $bar->finish();
        $this->newLine()->info('All anime data has been synced');

        return Command::SUCCESS;
    }
}
