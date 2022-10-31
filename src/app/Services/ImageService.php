<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anime;
use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class ImageService
 * @package App\Services
 */
class ImageService
{
    private const BASE_FOLDER_NAME = 'anime';

    /**
     * @param string $url
     * @param Anime  $anime
     * @return bool
     */
    public function upsert(string $url, Anime $anime): bool
    {
        if ($anime->image) {
            cloudinary()->destroy($anime->image->alias);
            $anime->image->delete();
        }

        $image = Image::create(
            [
                'model_type' => $anime::class,
                'model_id'   => $anime->id,
                'alias'      => $alias = sprintf(
                    '%s/%s/%s',
                    self::BASE_FOLDER_NAME,
                    $anime->id,
                    Str::random()
                ),
                'path'       => cloudinary()->uploadFile(
                    $url,
                    [
                        'public_id' => $alias,
                    ]
                )->getSecurePath(),
            ]
        )->save();

        if (File::exists($url)) {
            File::delete($url);
        }

        return $image;
    }
}
