<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VoiceActing;
use App\Repositories\Contracts\VoiceActingRepositoryInterface;
use App\Traits\CanGenerateNamesArray;

/**
 * Class VoiceActingService
 * @package App\Services
 */
class VoiceActingService
{
    use CanGenerateNamesArray;

    public function __construct(
        private readonly VoiceActingRepositoryInterface $voiceActingRepository
    ) {
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

        $newVoiceActing = $this->generateNamesArray($newVoiceActing);
        VoiceActing::upsert($newVoiceActing, ['name']);

        return $stored->toBase()->merge($newVoiceActing)->pluck('id')->toArray();
    }
}
