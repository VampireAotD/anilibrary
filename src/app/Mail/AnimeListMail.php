<?php

declare(strict_types=1);

namespace App\Mail;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimeListMail
 * @package App\Mail
 */
class AnimeListMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->onQueue(QueueEnum::MAIL_QUEUE->value)->onConnection('redis');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->from(config('mail.from.address'))
                    ->attachFromStorageDisk('lists', config('lists.anime.file'))
                    ->markdown('mail.list.anime-list-mail');
    }
}
