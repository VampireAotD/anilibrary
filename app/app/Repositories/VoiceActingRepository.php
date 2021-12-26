<?php

namespace App\Repositories;

use App\Models\VoiceActing;
use App\Repositories\Contracts\VoiceActing\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VoiceActingRepository
 * @package App\Repositories
 */
class VoiceActingRepository extends BaseRepository implements Repository
{
    /**
     * @return string
     */
    protected function resolveModel(): string
    {
        return VoiceActing::class;
    }

    /**
     * @param string $uuid
     * @return Model|null
     */
    public function findById(string $uuid): ?Model
    {
        return $this->query()->find($uuid);
    }

    /**
     * @param array $similarNames
     * @param array|string[] $attributes
     * @return Collection
     */
    public function findSimilarByNames(array $similarNames, array $attributes = ['*']): Collection
    {
        return $this->query()->select($attributes)->whereIn('name', $similarNames)->get();
    }
}
