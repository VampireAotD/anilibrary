<?php

declare(strict_types=1);

namespace App\Repositories\VoiceActing;

use App\Models\VoiceActing;
use App\Repositories\BaseRepository;
use App\Repositories\Traits\CanSearchByName;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class VoiceActingRepository
 * @package App\Repositories
 */
class VoiceActingRepository extends BaseRepository implements VoiceActingRepositoryInterface
{
    use CanSearchByName;

    /**
     * @return Builder|VoiceActing
     */
    protected function model(): Builder | VoiceActing
    {
        return VoiceActing::query();
    }

    /**
     * @param array        $data
     * @param string|array $uniqueBy
     * @return int
     */
    public function upsertMany(array $data, array | string $uniqueBy): int
    {
        return $this->model()->upsert($data, $uniqueBy);
    }
}
