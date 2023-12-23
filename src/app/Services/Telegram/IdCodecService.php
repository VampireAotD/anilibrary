<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Ramsey\Uuid\Uuid;
use Tuupola\Base62Proxy as Base62;

/**
 * Class IdCodecService
 * @package App\Services\Telegram
 */
readonly class IdCodecService
{
    public function encode(Uuid | string $id): string
    {
        if (is_string($id)) {
            $id = Uuid::fromString($id);
        }

        return Base62::encode($id->getBytes());
    }

    public function decode(string $encodedId): string
    {
        return Uuid::fromBytes(Base62::decode($encodedId))->toString();
    }
}
