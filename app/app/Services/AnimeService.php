<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Image;

/**
 * Class AnimeService
 * @package App\Services
 */
class AnimeService
{
    /**
     * @param array $data
     * @param array $voiceActing
     * @return Anime
     */
    public function create(array $data, array $voiceActing = []): Anime
    {
        $anime = Anime::create($data);

        $image = Image::create([
            'model_type' => $anime::class,
            'model_id' => $anime->id,
            'path' => cloudinary()->uploadFile($data['image'], [
                'folder' => 'anime/' . $anime->id
            ])->getSecurePath(),
        ]);

        if ($voiceActing) {
            $anime->voiceActing()->sync($voiceActing);
        }

        return $anime;
    }
}
