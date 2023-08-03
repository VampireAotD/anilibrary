<?php

declare(strict_types=1);

namespace App\Events\Pusher;

use App\DTO\Events\Pusher\ScrapeResultDTO;
use App\Enums\QueueEnum;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ScrapeResultEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $connection = 'redis';

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly ScrapeResultDTO $dto)
    {
        //
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     */
    public function broadcastQueue(): string
    {
        return QueueEnum::PUSHER_SCRAPER_RESPONSE_QUEUE->value;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'scrape.result';
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
        return new PrivateChannel('scraper.' . $this->dto->userId);
    }
}
