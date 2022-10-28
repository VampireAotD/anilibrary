<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Service\Anime\CreateDTO;
use App\Models\Anime;

/**
 * Class AnimeService
 * @package App\Services
 */
class AnimeService
{
    /**
     * @param CreateDTO $dto
     * @return Anime
     */
    public function create(CreateDTO $dto): Anime
    {
        return Anime::updateOrCreate(['title' => $dto->title], $dto->toArray());
    }
}
