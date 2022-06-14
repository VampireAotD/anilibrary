<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Anime;
use App\Repositories\Contracts\Tag\TagRepositoryInterface;

/**
 * Class AnimeService
 * @package App\Services
 */
class AnimeService
{
    /**
     * @param TagRepositoryInterface $tagRepository
     * @param ImageService           $imageService
     */
    public function __construct(
        private TagRepositoryInterface $tagRepository,
        private ImageService           $imageService
    ) {
    }

    /**
     * @param array $data
     * @return Anime
     */
    public function create(array $data): Anime
    {
        // TODO receive DTO
        $anime = Anime::updateOrCreate(['title' => $data['title']], $data);

        $this->imageService->upsert($data['image'], $anime);

        if ($data['voiceActing']) {
            $anime->voiceActing()->sync($data['voiceActing']);
        }

        if ($data['genres']) {
            $anime->genres()->sync($data['genres']);
        }

        if ($data['telegramId']) {
            $anime->tags()->sync(
                $this->tagRepository->findByTelegramId(
                    $data['telegramId']
                ),
                false
            );
        }

        return $anime;
    }
}
