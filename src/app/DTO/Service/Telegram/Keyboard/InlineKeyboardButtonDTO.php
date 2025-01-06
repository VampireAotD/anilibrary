<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\Keyboard;

final readonly class InlineKeyboardButtonDTO
{
    public function __construct(
        public string  $text,
        public ?string $url = null,
        public ?string $callbackData = null
    ) {
    }
}
