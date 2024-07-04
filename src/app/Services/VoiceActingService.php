<?php

declare(strict_types=1);

namespace App\Services;

use App\Filters\QueryFilterInterface;
use App\Models\VoiceActing;
use App\Repositories\VoiceActing\VoiceActingRepositoryInterface;
use Illuminate\Support\LazyCollection;

final readonly class VoiceActingService
{
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
     * @param array<array{name: string}> $voiceActing
     * @return array<string> Array of voice acting ids
     */
    public function sync(array $voiceActing): array
    {
        $names  = collect($voiceActing)->pluck('name');
        $stored = $this->voiceActingRepository->findByNames($names->toArray());

        // Find difference between stored voice acting and new ones
        $newVoiceActing = $names->diff($stored->pluck('name'))->map(
            fn(string $voiceActing) => ['name' => $voiceActing]
        );

        // If there is new voice acting - upsert them and get their ids
        if ($newVoiceActing->isNotEmpty()) {
            $this->voiceActingRepository->upsertMany($newVoiceActing->toArray(), ['name']);
            $stored = $this->voiceActingRepository->findByNames($names->toArray());
        }

        return $stored->pluck('id')->toArray();
    }
}
