<?php

declare(strict_types=1);

namespace App\Mail;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimeListMail
 * @package App\Mail
 */
class AnimeListMail extends Mailable
{
    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->onConnection('redis');
        $this->onQueue(QueueEnum::MAIL_QUEUE->value);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->from(config('admin.email'))
            ->attach(config('filesystems.animeListPath'))
            ->markdown('emails.lists.anime-list');
    }
}
