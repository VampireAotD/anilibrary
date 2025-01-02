<?php

declare(strict_types=1);

namespace App\Mail\Anime;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class NotUpdatedAnimeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     * @param array<string, string> $failedList
     */
    public function __construct(public readonly array $failedList)
    {
        $this->onConnection('redis')->onQueue(QueueEnum::MAIL_QUEUE->value);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Failed to update unreleased anime',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.anime.not-updated-anime',
            with    : [
                'failed' => $this->failedList,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return list<Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
