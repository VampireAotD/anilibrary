<?php

declare(strict_types=1);

namespace App\Jobs\Elasticsearch;

use App\Enums\Elasticsearch\IndexEnum;
use App\Enums\QueueEnum;
use App\Models\Anime;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpsertAnimeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Anime $anime)
    {
        $this->afterCommit()->onQueue(QueueEnum::UPSERT_ANIME_IN_ELASTICSEARCH_QUEUE->value)->onConnection('redis');
    }

    /**
     * Execute the job.
     */
    public function handle(Client $client): void
    {
        try {
            $client->index([
                'index' => IndexEnum::ANIME_INDEX->value,
                'id'    => $this->anime->id,
                'body'  => $this->anime->toJson(),
            ]);
        } catch (ClientResponseException | MissingParameterException | ServerResponseException $exception) {
            logger()->error('Upsert anime job', [
                'anime_id'          => $this->anime->id,
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);
        }
    }
}
