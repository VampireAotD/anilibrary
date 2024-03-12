<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Telegram;

use App\Services\Telegram\EncoderService;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class EncoderServiceTest extends TestCase
{
    protected EncoderService $encoderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->encoderService = new EncoderService();
    }

    public function testWillThrowAnExceptionIfValueIsNotUuid(): void
    {
        $this->expectException(InvalidUuidStringException::class);
        $this->encoderService->encodeId(Str::random());
    }

    public function testCanEncodeUuidString(): void
    {
        $uuidString = Str::orderedUuid()->toString();
        $encoded    = $this->encoderService->encodeId($uuidString);

        $this->assertIsString($encoded);
    }

    public function testCanEncodeUuidObject(): void
    {
        $uuid    = Str::orderedUuid();
        $encoded = $this->encoderService->encodeId($uuid);

        $this->assertIsString($encoded);
    }

    public function testCanDecodeToOriginalUuid(): void
    {
        $uuid    = Str::orderedUuid();
        $encoded = $this->encoderService->encodeId($uuid);
        $decoded = $this->encoderService->decodeId($encoded);

        $this->assertEquals($uuid->toString(), $decoded);
    }
}
