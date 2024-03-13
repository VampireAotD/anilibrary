<?php

declare(strict_types=1);

namespace App\Services;

use App\Filters\QueryFilterInterface;
use App\Models\VoiceActing;
use App\Repositories\VoiceActing\VoiceActingRepositoryInterface;
use App\Traits\CanTransformArray;
use Illuminate\Support\LazyCollection;

final readonly class VoiceActingService
{
    use CanTransformArray;

    public function __construct(private VoiceActingRepositoryInterface $voiceActingRepository)
    {
    }

    /**
     * @param array<QueryFilterInterface> $filters
     * @return LazyCollection<int, VoiceActing>
     */
    public function all(array $filters = []): LazyCollection
    {
        return $this->voiceActingRepository->withFilters($filters)->getAll();
    }

    /**
     * @param string[] $voiceActing
     * @return string[]
     */
    public function sync(array $voiceActing): array
    {
        $stored         = $this->voiceActingRepository->findByNames($voiceActing);
        $newVoiceActing = array_diff($voiceActing, $stored->pluck('name')->toArray());

        if (!$newVoiceActing) {
            return $stored->pluck('id')->toArray();
        }

        $newVoiceActing = $this->toAssociativeArrayWithUuid('name', $newVoiceActing);

        $this->voiceActingRepository->upsertMany($newVoiceActing, ['name']);

        return $stored->toBase()->merge($newVoiceActing)->pluck('id')->toArray();
    }
}
