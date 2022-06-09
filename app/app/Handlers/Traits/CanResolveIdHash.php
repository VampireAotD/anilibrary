<?php

namespace App\Handlers\Traits;

use Ramsey\Uuid\Uuid;
use Tuupola\Base62\GmpEncoder;
use Tuupola\Base62\PhpEncoder;

/**
 * Trait CanResolveIdHash
 * @package App\Handlers\Traits
 */
trait CanResolveIdHash
{
    protected GmpEncoder | PhpEncoder $encoder;

    public function resolveBindings(): void
    {
        $this->encoder = extension_loaded('gmp') ? new GmpEncoder() : new PhpEncoder();
    }

    /**
     * @param string $id
     * @return string
     */
    public function encode(string $id): string
    {
        $id = Uuid::fromString($id);

        return $this->encoder->encode($id->getBytes());
    }

    /**
     * @param string $encodedId
     * @return string
     */
    public function decode(string $encodedId): string
    {
        return Uuid::fromBytes($this->encoder->decode($encodedId));
    }
}
