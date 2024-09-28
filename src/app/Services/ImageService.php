<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anime;
use App\Models\Image;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final readonly class ImageService
{
    public function attachEncodedImageToAnime(string $image, Anime $anime): void
    {
        $content = preg_replace('#data:image/\w+;base64,#', '', $image);

        $hash = hash('sha512', base64_decode($content));

        if ($duplicate = Image::query()->where('hash', $hash)->first()) {
            $anime->attachImage($duplicate);
            return;
        }

        try {
            $publicId = sprintf('%s/%s', $anime->id, Str::random());
            $response = cloudinary()->uploadFile(
                file   : $image,
                options: [
                    'public_id'     => $publicId,
                    'upload_preset' => config('cloudinary.upload_preset'),
                ]
            );

            $image = Image::query()->create([
                'path' => $response->getSecurePath(),
                'name' => $response->getPublicId(),
                'hash' => $hash,
            ]);

            // Same situation as in `AnimeService`, anime will always
            // have a default image path, so need to check if it is an
            // actual image in DB and delete it if it is
            if (!$anime->image->is_default) {
                cloudinary()->destroy($anime->image->name);
            }

            $anime->attachImage($image);
        } catch (ApiError $exception) {
            Log::error('Failed to upload image', [
                'anime'             => $anime->id,
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);
        }
    }
}
