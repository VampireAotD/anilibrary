<?php

declare(strict_types=1);

namespace App\DTO\Service\Telegram\User;

use App\DTO\Contracts\FromArray;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class TelegramUserDTO implements FromArray, Arrayable
{
    public function __construct(
        public int     $telegramId,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $username = null
    ) {
    }

    /**
     * @param array{id: int, first_name?: string, last_name?: string, username?: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['username'] ?? null
        );
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
            'username'    => $this->username,
        ];
    }
}
