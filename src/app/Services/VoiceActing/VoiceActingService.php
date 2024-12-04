<?php

declare(strict_types=1);

namespace App\Services\VoiceActing;

use App\Filters\QueryFilterInterface;
use App\Models\VoiceActing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\LazyCollection;

final readonly class VoiceActingService
{
    /**
     * @param list<QueryFilterInterface> $filters
     * @return LazyCollection<int, VoiceActing>
     */
    public function all(array $filters = []): LazyCollection
    {
        return VoiceActing::filter($filters)->lazy();
    }

    /**
     * @param array<array{name: string}> $voiceActing
     * @return list<string> Array of voice acting ids
     */
    public function sync(array $voiceActing): array
    {
        $names  = collect($voiceActing)->pluck('name');
        $stored = $this->findByNames($names->toArray());

        // Find difference between stored voice acting and new ones
        $newVoiceActing = $names->diff($stored->pluck('name'))->map(
            static fn(string $voiceActing) => ['name' => $voiceActing]
        );

        // If there is new voice acting - upsert them and get their ids
        if ($newVoiceActing->isNotEmpty()) {
            VoiceActing::upsert($newVoiceActing->toArray(), ['name']);
            $stored = $this->findByNames($names->toArray());
        }

        return $stored->pluck('id')->toArray();
    }

    /**
     * @param list<string> $names
     */
    private function findByNames(array $names): Collection
    {
        return VoiceActing::select(['id', 'name'])->whereIn('name', $names)->get();
    }
}
