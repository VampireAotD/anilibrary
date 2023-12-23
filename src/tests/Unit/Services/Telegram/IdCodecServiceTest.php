<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Telegram;

use App\Services\Telegram\IdCodecService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class IdCodecServiceTest extends TestCase
{
    protected IdCodecService $idCodecService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->idCodecService = new IdCodecService();
    }

    public function testWillThrowAnExceptionIfValueIsNotUuid(): void
    {
        $this->expectException(InvalidUuidStringException::class);
        $this->idCodecService->encode(Str::random());
    }

    public function testCanEncodeUuidString(): void
    {
        $uuidString = Str::orderedUuid()->toString();
        $encoded    = $this->idCodecService->encode($uuidString);

        $this->assertIsString($encoded);
    }

    public function testCanEncodeUuidObject(): void
    {
        $uuid    = Str::orderedUuid();
        $encoded = $this->idCodecService->encode($uuid);

        $this->assertIsString($encoded);
    }

    public function testCanDecodeToOriginalUuid(): void
    {
        $uuid    = Str::orderedUuid();
        $encoded = $this->idCodecService->encode($uuid);
        $decoded = $this->idCodecService->decode($encoded);

        $this->assertEquals($uuid->toString(), $decoded);
    }
}
