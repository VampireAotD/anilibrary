<?php

declare(strict_types=1);

namespace App\DTO\UseCase\Metric;

use App\Models\Anime;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MetricDTO
 * @package App\DTO\UseCase\Metric
 * @template-implements Arrayable<string, mixed>
 */
readonly class MetricDTO implements Arrayable
{
    /**
     * @param int                    $animeCount
     * @param int                    $usersCount
     * @param array<int>             $animePerMonth
     * @param array<string, int>     $animePerDomain
     * @param Collection<int, Anime> $latestAnime
     */
    public function __construct(
        public int        $animeCount,
        public int        $usersCount,
        public array      $animePerMonth,
        public array      $animePerDomain,
        public Collection $latestAnime,
    ) {
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'animeCount'     => $this->animeCount,
            'usersCount'     => $this->usersCount,
            'animePerMonth'  => $this->animePerMonth,
            'animePerDomain' => $this->animePerDomain,
            'latestAnime'    => $this->latestAnime,
        ];
    }
}
