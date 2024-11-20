<?php

declare(strict_types=1);

namespace App\DTO\Service\Invitation;

use App\Enums\Invitation\StatusEnum;
use Illuminate\Contracts\Support\Arrayable;

final readonly class InvitationDTO implements Arrayable
{
    public function __construct(
        public string     $email,
        public StatusEnum $status
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
            'email'  => $this->email,
            'status' => $this->status,
        ];
    }
}
