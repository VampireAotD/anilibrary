<?php

declare(strict_types=1);

namespace App\Listeners\User;

use App\Events\User\PasswordChangedEvent;
use App\Mail\User\PasswordChangedMail;
use Illuminate\Support\Facades\Mail;

final class PasswordChangedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordChangedEvent $event): void
    {
        Mail::to($event->user)->queue(new PasswordChangedMail($event->user));
    }
}
