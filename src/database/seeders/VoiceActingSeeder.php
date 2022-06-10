<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VoiceActingSeederEnum;
use App\Models\VoiceActing;
use App\Services\Traits\CanPrepareDataForBatchInsert;
use Illuminate\Database\Seeder;

/**
 * Class VoiceActingSeeder
 * @package Database\Seeders
 */
class VoiceActingSeeder extends Seeder
{
    use CanPrepareDataForBatchInsert;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $voiceActing = [
            [
                'name' => VoiceActingSeederEnum::ANIDUB->value,
            ],
            [
                'name' => VoiceActingSeederEnum::ANILIBRIA->value,
            ],
            [
                'name' => VoiceActingSeederEnum::STUDIO_BAND->value,
            ],
            [
                'name' => VoiceActingSeederEnum::ANIMEVOST->value,
            ],
            [
                'name' => VoiceActingSeederEnum::DREAM_CAST->value,
            ],
        ];

        VoiceActing::insert($this->prepareArrayForInsert($voiceActing));
    }
}
