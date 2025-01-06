<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Enums\QueueEnum;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

final class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onConnection('redis')->onQueue(QueueEnum::MAIL_QUEUE->value);
    }
}
