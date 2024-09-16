<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Image;
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

    public function testWillNotUploadImageIfItIsAlreadyInDatabase(): void
    {
        // Create anime with no image
        $anime = $this->createAnime();

        // By default, anime will have a default image, but that will be due to `withDefault` method on relation,
        // in database there must be no records
        $this->assertDatabaseCount(Image::class, 0);
        $this->assertEquals(0, $anime->image()->count());
        $this->assertNotNull($anime->image);
        $this->assertTrue($anime->image->is_default);

        $image = Image::factory()->create([
            'path' => $imageContent = $this->faker->imageUrl,
            'hash' => $this->faker->sha512($imageContent),
            'name' => $this->faker->word,
        ]);
        $this->assertDatabaseCount($image, 1);

        $this->imageService->attachEncodedImageToAnime($this->faker->randomAnimeImage($imageContent), $anime);
        $anime->refresh();

        // If image is already in database, it should not be uploaded again, but should be attached to anime
        $this->assertDatabaseCount($image, 1);
        $this->assertEquals(1, $anime->image()->count());
        $this->assertFalse($anime->image->is_default);
        $this->assertEquals($image->hash, $anime->image->hash);
    }

    public function testWillNotDeleteImageIfAnimeDontHaveOneOrImageHasDefaultPathFromConfig(): void
    {
        // Create anime with no image
        $anime = $this->createAnime();

        Cloudinary::shouldReceive('destroy')->never();
        Cloudinary::shouldReceive('uploadFile')->once();
        Cloudinary::shouldReceive([
            'getSecurePath' => $this->faker->imageUrl,
            'getPublicId'   => $this->faker->word,
        ])->once();

        $this->imageService->attachEncodedImageToAnime($this->faker->randomAnimeImage(), $anime);
    }

    public function testWillDeleteImageIfAnimeAlreadyHaveOneAndItHasNotADefaultPath(): void
    {
        $anime = $this->createAnimeWithRelations();

        Cloudinary::shouldReceive('destroy')->once();
        Cloudinary::shouldReceive('uploadFile')->once();
        Cloudinary::shouldReceive([
            'getSecurePath' => $this->faker->imageUrl,
            'getPublicId'   => $this->faker->word,
        ])->once();

        $this->imageService->attachEncodedImageToAnime($this->faker->randomAnimeImage(), $anime);
    }

    public function testWillLogErrorIfFailedToUploadEncodedAnimeImage(): void
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

        $this->imageService->attachEncodedImageToAnime($this->faker->randomAnimeImage(), $anime);
    }

    public function testCanAttachAndUploadEncodedAnimeImage(): void
    {
        $anime = $this->createAnime();

        // Ensure that anime has default image
        $this->assertNotNull($anime->image);
        $this->assertTrue($anime->image->is_default);

        Cloudinary::shouldReceive('destroy')->never();
        Cloudinary::shouldReceive('uploadFile')->once();
        Cloudinary::shouldReceive([
            'getSecurePath' => $path = $this->faker->imageUrl,
            'getPublicId'   => $this->faker->word,
        ])->once();

        $this->imageService->attachEncodedImageToAnime($this->faker->randomAnimeImage(), $anime);

        $anime->refresh();

        // Ensure that anime has image store in DB and path from CDN
        $this->assertFalse($anime->image->is_default);
        $this->assertNotNull($anime->image->id);
        $this->assertNotNull($anime->image->hash);
        $this->assertNotNull($anime->image->name);
        $this->assertEquals($path, $anime->image->path);
    }
}
