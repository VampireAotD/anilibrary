<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anime;
use App\Models\Image;
use Illuminate\Support\Str;

final class ImageService
{
    private const string BASE_FOLDER = 'anime';

    /**
     * @param string $image
     * @param Anime  $anime
     * @return Image
     */
    public function upsert(string $image, Anime $anime): Image
    {
        if ($anime->image) {
            cloudinary()->destroy($anime->image->alias);
        }

        return Image::query()->updateOrCreate([
            'model_id'   => $anime->id,
            'model_type' => $anime::class,
        ], [
            'alias' => $alias = sprintf('%s/%s/%s', self::BASE_FOLDER, $anime->id, Str::random()),
            'path'  => cloudinary()->uploadFile($image, ['public_id' => $alias])->getSecurePath(),
        ]);
    }
}
