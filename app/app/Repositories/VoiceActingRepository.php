<?php

namespace App\Repositories;

use App\Models\VoiceActing;
use App\Repositories\Traits\CanSearchBySimilarNames;

/**
 * Class VoiceActingRepository
 * @package App\Repositories
 */
class VoiceActingRepository extends BaseRepository
{
    use CanSearchBySimilarNames;

    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return VoiceActing::class;
    }
}
