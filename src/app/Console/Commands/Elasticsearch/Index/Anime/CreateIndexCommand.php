<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Console\Commands\Elasticsearch\Index\Anime\Traits\IndexConfigurationTrait;
use App\Enums\Elasticsearch\IndexEnum;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class CreateIndexCommand extends Command
{
    use IndexConfigurationTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:create-anime-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create anime index in Elasticsearch';

    /**
     * Execute the console command.
     * @psalm-suppress InvalidArgument
     */
    public function handle(Client $client): int
    {
        if ($client->indices()->exists(['index' => IndexEnum::ANIME_INDEX->value])->asBool()) {
            $this->warn('Index already exists!');

            return Command::FAILURE;
        }

        $client->indices()->create([
            'index' => IndexEnum::ANIME_INDEX->value,
            'body'  => [
                'settings' => $this->getIndexSettings(),
                'mappings' => $this->getIndexMappings(),
            ],
        ]);

        $this->info('Successfully created index');

        return Command::SUCCESS;
    }
}
