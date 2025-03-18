<?php

declare(strict_types=1);

namespace Tests\Concerns\Mocks;

use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @mixin TestCase
 */
trait CanCreateCloudinaryMock
{
    protected readonly Cloudinary | MockObject $cloudinary;

    protected readonly UploadApi | MockObject $uploadApi;

    /**
     * @throws Exception
     */
    protected function setUpFakeCloudinary(): void
    {
        $this->cloudinary = $this->createMock(Cloudinary::class);
        $this->uploadApi  = $this->createMock(UploadApi::class);

        $this->cloudinary->method('uploadApi')->willReturn($this->uploadApi);

        $this->app->instance(Cloudinary::class, $this->cloudinary);
    }

    protected function createCloudinaryApiResponse(array $data, int $statusCode = 200): ApiResponse
    {
        return new ApiResponse($data, ['headers' => [], 'statusCode' => $statusCode]);
    }
}
