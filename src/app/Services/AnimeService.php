<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\CreateDTO;
use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepositoryInterface;

/**
 * Class AnimeService
 * @package App\Services
 */
class AnimeService
{
    public function __construct(private readonly AnimeRepositoryInterface $animeRepository)
    {
    }

    /**
     * @param CreateDTO $dto
     * @return Anime
     */
    public function create(CreateDTO $dto): Anime
    {
        return Anime::query()->updateOrCreate(['title' => $dto->title], $dto->toArray());
    }

    public function findByUrl(string $url): ?Anime
    {
        return $this->animeRepository->findByUrl($url);
    }

    public function findByTitleAndSynonyms(array $data): ?Anime
    {
        return $this->animeRepository->findByTitleAndSynonyms($data);
    }
}
