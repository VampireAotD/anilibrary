<?php

namespace App\Services;

use App\Models\Anime;
use App\Models\Image;
use App\Repositories\Contracts\Tag\Repository as TagRepository;

/**
 * Class AnimeService
 * @package App\Services
 */
class AnimeService
{
    public function __construct(
        private TagRepository $tagRepository,
        private ImageService  $imageService
    )
    {
    }

    /**
     * @param array $data
     * @return Anime
     */
    public function create(array $data): Anime
    {
        $anime = Anime::updateOrCreate(['title' => $data['title'], 'url' => $data['url']], $data);

        $this->imageService->upsert($data['image'], $anime);

        if (isset($data['voiceActing']) && $data['voiceActing']) {
            $anime->voiceActing()->sync($data['voiceActing']);
        }

        if (isset($data['genres']) && $data['genres']) {
            $anime->genres()->sync($data['genres']);
        }

        if (isset($data['telegramId']) && $data['telegramId']) {
            $anime->tags()->sync(
                $this->tagRepository->findByTelegramId($data['telegramId']),
                false
            );
        }

        return $anime;
    }
}
