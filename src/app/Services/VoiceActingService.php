<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VoiceActing;
use App\Services\Traits\CanPrepareDataForBatchInsert;

/**
 * Class VoiceActingService
 * @package App\Services
 */
class VoiceActingService
{
    use CanPrepareDataForBatchInsert;

    /**
     * @param array $data
     * @return bool
     */
    public function batchInsert(array $data): bool
    {
        return VoiceActing::insert($this->prepareArrayForInsert($data));
    }
}
