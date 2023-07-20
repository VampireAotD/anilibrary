<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\Telegram\CreateUserDTO;
use App\Enums\QueueEnum;
use App\Services\TelegramUserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly CreateUserDTO $dto)
    {
        $this->onQueue(QueueEnum::REGISTER_TELEGRAM_USER_QUEUE->value)->onConnection('redis');
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramUserService $telegramUserService): void
    {
        $telegramUserService->upsert($this->dto);
    }
}
