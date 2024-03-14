<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Console\Commands\Elasticsearch\Index\Anime\Concerns\IndexConfiguration;
use App\Enums\Elasticsearch\IndexEnum;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class UpdateIndexMappingsCommand extends Command
{
    use IndexConfiguration;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:update-anime-index-mappings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update anime index mappings in Elasticsearch';

    /**
     * Execute the console command.
     * @psalm-suppress InvalidArgument
     */
    public function handle(Client $manager): int
    {
        $manager->indices()->putMapping([
            'index' => IndexEnum::ANIME_INDEX->value,
            'body'  => $this->getIndexMappings(),
        ]);

        $this->info(sprintf('Mappings for %s index were updated', IndexEnum::ANIME_INDEX->value));

        return Command::SUCCESS;
    }
}
