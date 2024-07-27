<?php

declare(strict_types=1);

namespace App\Events\Scraper;

use App\DTO\Events\Scraper\ScrapeAnimeResultDTO;
use App\Enums\QueueEnum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ScrapeAnimeResultEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $connection = 'redis';

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly ScrapeAnimeResultDTO $dto)
    {
        //
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     */
    public function broadcastQueue(): string
    {
        return QueueEnum::SOCKET_QUEUE->value;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'scrape.anime.result';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'type'    => $this->dto->resultType->value,
            'message' => $this->dto->message,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('scrape.anime.' . $this->dto->userId);
    }
}
