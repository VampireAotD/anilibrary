<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anime;
use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final readonly class ImageService
{
    public function upsert(string $image, Anime $anime): void
    {
        // Same situation as in `AnimeService`, anime will always
        // have a default image path, so need to check if it is an
        // actual image in DB and delete it if it is
        if (!$anime->image->default) {
            /** @phpstan-ignore-next-line For CI/CD */
            Cloudinary::destroy($anime->image->alias);
        }

        try {
            $alias = sprintf('%s/%s', $anime->id, Str::random());

            /** @phpstan-ignore-next-line For CI/CD */
            $path = Cloudinary::uploadFile(
                file   : $image,
                options: [
                    'folder'    => config('cloudinary.default_folder'),
                    'public_id' => $alias,
                ]
            )->getSecurePath();

            $anime->image()->updateOrCreate([
                'path'  => $path,
                'alias' => $alias,
            ]);
        } catch (ApiError $exception) {
            Log::error('Failed to upload image', [
                'anime'             => $anime->id,
                'exception_trace'   => $exception->getTraceAsString(),
                'exception_message' => $exception->getMessage(),
            ]);
        }
    }
}
