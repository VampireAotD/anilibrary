<?php

declare(strict_types=1);

namespace App\Repositories\VoiceActing;

use App\Models\VoiceActing;
use App\Repositories\Traits\CanSearchByName;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class VoiceActingRepository
 * @package App\Repositories
 */
class VoiceActingRepository implements VoiceActingRepositoryInterface
{
    use CanSearchByName;

    /**
     * @var Builder|VoiceActing
     */
    protected Builder | VoiceActing $query;

    public function __construct()
    {
        $this->query = VoiceActing::query();
    }

    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, array | string $uniqueBy): int
    {
        return $this->query->upsert($data, $uniqueBy);
    }
}
