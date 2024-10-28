<?php

declare(strict_types=1);

namespace App\Jobs\Telegram;

use App\DTO\Service\Telegram\User\TelegramUserDTO;
use App\Enums\QueueEnum;
use App\Exceptions\Service\Telegram\TelegramUserException;
use App\Services\Telegram\TelegramUserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class RegisterTelegramUserJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly TelegramUserDTO $dto)
    {
        $this->onQueue(QueueEnum::TELEGRAM_QUEUE->value)->onConnection('redis');
    }

    /**
     * Execute the job.
     * @throws TelegramUserException|Throwable
     */
    public function handle(TelegramUserService $telegramUserService): void
    {
        $telegramUserService->register($this->dto);
    }
}
