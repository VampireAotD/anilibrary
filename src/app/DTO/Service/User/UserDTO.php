<?php

declare(strict_types=1);

namespace App\DTO\Service\User;

use App\DTO\Contracts\FromArray;
use Illuminate\Contracts\Support\Arrayable;
use SensitiveParameter;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class UserDTO implements Arrayable, FromArray
{
    public function __construct(
        public string                       $name,
        public string                       $email,
        #[SensitiveParameter] public string $password
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['password']
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
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ];
    }
}
