<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\VoiceActing;
use App\Repositories\Contracts\VoiceActingRepositoryInterface;
use App\Repositories\Traits\CanSearchByName;

/**
 * Class VoiceActingRepository
 * @package App\Repositories
 */
class VoiceActingRepository extends BaseRepository implements VoiceActingRepositoryInterface
{
    use CanSearchByName;

    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return VoiceActing::class;
    }
}
