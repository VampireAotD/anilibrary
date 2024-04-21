<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch\Index\Anime;

use App\Console\Commands\Elasticsearch\Index\Anime\Concerns\IndexConfiguration;
use App\Enums\Elasticsearch\IndexEnum;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
     */
    public function handle(Client $manager): int
    {
        /** @psalm-suppress InvalidArgument */
        try {
            $manager->indices()->putMapping([
                'index' => [IndexEnum::ANIME_INDEX->value],
                'body'  => $this->getIndexMappings(),
            ]);
        } catch (ClientResponseException | MissingParameterException | ServerResponseException $e) {
            Log::error("Elasticsearch anime index mappings update failed", [
                'exception_trace'   => $e->getTraceAsString(),
                'exception_message' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }

        $this->info(sprintf('Mappings for %s index were updated', IndexEnum::ANIME_INDEX->value));

        return self::SUCCESS;
    }
}
