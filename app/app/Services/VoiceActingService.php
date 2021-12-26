<?php

namespace App\Services;

use App\Models\VoiceActing;
use Illuminate\Support\Str;

/**
 * Class VoiceActingService
 * @package App\Services
 */
class VoiceActingService
{
    /**
     * @param array $data
     * @return bool
     */
    public function batchInsert(array $data): bool
    {
        $voiceActing = array_reduce(
            $data,
            function (array $newVoiceActing, string $voiceActingName) {
                $newVoiceActing[] = [
                    'id' => Str::uuid(),
                    'name' => $voiceActingName
                ];

                return $newVoiceActing;
            },
            []
        );

        return VoiceActing::insert($voiceActing);
    }
}
