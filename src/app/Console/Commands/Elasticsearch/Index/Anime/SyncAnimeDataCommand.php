<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Enums\Elasticsearch\IndexEnum;
use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;
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
     */
    public function handle(AnimeRepositoryInterface $animeRepository, Client $client): int
    {
        $this->info('Trying to sync all anime into Elasticsearch index...');

        $animeList = $animeRepository->getAll(
            ['id', 'title', 'status', 'rating', 'episodes'],
            [
                'synonyms:anime_id,synonym',
                'tags:id,name',
                'genres:id,name',
                'voiceActing:id,name',
            ]
        );

        $bar = $this->output->createProgressBar($animeList->count());

        /** @psalm-suppress InvalidTemplateParam */
        $animeList->chunk(100)->each(
            function (Collection $collection) use ($client, $bar) {
                $batch = ['body' => []];

                $collection->each(
                /** @phpstan-ignore-next-line */
                    function (Anime $anime) use (&$batch, $bar) {
                        $batch['body'][] = [
                            'index' => [
                                '_index' => IndexEnum::ANIME_INDEX->value,
                                '_id'    => $anime->id,
                            ],
                            'body'  => $anime->toJson(),
                        ];

                        $bar->advance();
                    }
                );

                $client->bulk($batch);
            }
        );

        $bar->finish();
        $this->newLine()->info('All anime data has been synced');

        return Command::SUCCESS;
    }
}
