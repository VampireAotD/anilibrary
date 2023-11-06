<?php

declare(strict_types=1);

namespace App\Repositories\AnimeUrl;

interface AnimeUrlRepositoryInterface
{
    public function countAnimePerDomain(): array;
}
