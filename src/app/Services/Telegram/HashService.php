<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Ramsey\Uuid\Uuid;
use Tuupola\Base62\GmpEncoder;
use Tuupola\Base62\PhpEncoder;

/**
 * Class HashService
 * @package App\Services\Telegram
 */
readonly class HashService
{
    protected GmpEncoder | PhpEncoder $encoder;

    public function __construct()
    {
        $this->encoder = extension_loaded('gmp') ? new GmpEncoder() : new PhpEncoder();
    }

    /**
     * @param string $id
     * @return string
     */
    public function encodeUuid(string $id): string
    {
        $id = Uuid::fromString($id);

        return $this->encoder->encode($id->getBytes());
    }

    /**
     * @param string $encodedId
     * @return string
     */
    public function decodeUuid(string $encodedId): string
    {
        return Uuid::fromBytes($this->encoder->decode($encodedId))->toString();
    }
}
