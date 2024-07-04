<?php

declare(strict_types=1);

namespace App\Jobs\Image;

use App\Enums\QueueEnum;
use App\Models\Anime;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class UploadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Anime $anime, public readonly string $image)
    {
        $this->onConnection('redis')->onQueue(QueueEnum::IMAGE_STORAGE_QUEUE->value)->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(ImageService $imageService): void
    {
        $imageService->upsert($this->image, $this->anime);
    }
}
