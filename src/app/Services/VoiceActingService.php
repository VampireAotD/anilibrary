<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\VoiceActing\VoiceActingRepositoryInterface;
use App\Traits\CanTransformArray;

/**
 * Class VoiceActingService
 * @package App\Services
 */
final class VoiceActingService
{
    use CanTransformArray;

    public function __construct(private readonly VoiceActingRepositoryInterface $voiceActingRepository)
    {
    }

    /**
     * @param string[] $voiceActing
     * @return string[]
     */
    public function sync(array $voiceActing): array
    {
        $stored         = $this->voiceActingRepository->findSimilarByNames($voiceActing);
        $newVoiceActing = array_diff($voiceActing, $stored->pluck('name')->toArray());

        if (!$newVoiceActing) {
            return $stored->pluck('id')->toArray();
        }

        $newVoiceActing = $this->toAssociativeArrayWithUuid('name', $newVoiceActing);

        $this->voiceActingRepository->upsertMany($newVoiceActing, ['name']);

        return $stored->toBase()->merge($newVoiceActing)->pluck('id')->toArray();
    }
}
