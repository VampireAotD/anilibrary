<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Services\ImageService;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\Concerns\CanCreateMocks;
use Tests\Concerns\Fake\CanCreateFakeAnime;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use CanCreateMocks;
    use CanCreateFakeAnime;

    protected ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFakeCloudinary();

        $this->imageService = $this->app->make(ImageService::class);
    }

    public function testWillNotDeleteImageIfAnimeDontHaveOneOrImageHasDefaultPathFromConfig(): void
    {
        // Create anime with no image
        $anime = $this->createAnime();

        Cloudinary::shouldReceive('destroy')->never();
        Cloudinary::shouldReceive('uploadFile->getSecurePath')->once()->andReturn($this->faker->imageUrl);

        $this->imageService->upsert($this->faker->imageUrl, $anime);
    }

    public function testWillDeleteImageIfAnimeAlreadyHaveOneAndItHasNotADefaultPath(): void
    {
        $anime = $this->createAnimeWithRelations();

        Cloudinary::shouldReceive('destroy')->once();
        Cloudinary::shouldReceive('uploadFile->getSecurePath')->once()->andReturn($this->faker->imageUrl);

        $this->imageService->upsert($this->faker->imageUrl, $anime);
    }

    public function testWillLogErrorIfFailedToUploadImage(): void
    {
        $anime     = $this->createAnime();
        $exception = new ApiError('test');

        Cloudinary::shouldReceive('destroy')->never();
        Cloudinary::shouldReceive('uploadFile')->once()->andThrow($exception);

        Log::shouldReceive('error')->once()->with('Failed to upload image', [
            'anime'             => $anime->id,
            'exception_trace'   => $exception->getTraceAsString(),
            'exception_message' => $exception->getMessage(),
        ]);

        $this->imageService->upsert($this->faker->randomAnimeImage(), $anime);
    }

    public function testCanUploadImage(): void
    {
        $anime = $this->createAnime();

        // Ensure that anime has default image
        $this->assertNotNull($anime->image);
        $this->assertTrue($anime->image->default);

        Cloudinary::shouldReceive('destroy')->never();
        Cloudinary::shouldReceive('uploadFile->getSecurePath')->once()->andReturn($path = $this->faker->imageUrl);

        $this->imageService->upsert($this->faker->randomAnimeImage(), $anime);

        $anime->refresh();

        // Ensure that anime has image store in DB and path from CDN
        $this->assertFalse($anime->image->default);
        $this->assertNotNull($anime->image->id);
        $this->assertNotNull($anime->image->alias);
        $this->assertEquals($path, $anime->image->path);
    }
}
