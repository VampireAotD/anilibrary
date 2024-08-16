<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\User;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class RegisterTelegramUserDTO implements Arrayable
{
    public function __construct(
        public int     $telegramId,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $userName = null
    ) {
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'telegram_id' => $this->telegramId,
            'first_name'  => $this->firstName,
            'last_name'   => $this->lastName,
            'username'    => $this->userName,
        ];
    }
}
