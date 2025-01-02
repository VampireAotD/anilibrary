<?php

declare(strict_types=1);

namespace App\DTO\Service\UserAnimeList;

use App\Enums\UserAnimeList\StatusEnum;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @template-implements Arrayable<string, mixed>
 */
final readonly class UpdateAnimeInListDTO implements Arrayable
{
    public function __construct(
        public User       $user,
        public string     $animeId,
        public StatusEnum $status,
        public ?float     $rating = null,
        public ?int       $episodes = null
    ) {
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'status'   => $this->status,
            'rating'   => $this->rating,
            'episodes' => $this->episodes,
        ]);
    }
}
