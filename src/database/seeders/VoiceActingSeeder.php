<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VoiceActingSeederEnum;
use App\Models\VoiceActing;
use App\Services\Traits\CanGenerateNamesArray;
use Illuminate\Database\Seeder;

/**
 * Class VoiceActingSeeder
 * @package Database\Seeders
 */
class VoiceActingSeeder extends Seeder
{
    use CanGenerateNamesArray;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $voiceActing = [
            VoiceActingSeederEnum::ANIDUB->value,
            VoiceActingSeederEnum::ANILIBRIA->value,
            VoiceActingSeederEnum::STUDIO_BAND->value,
            VoiceActingSeederEnum::ANIMEVOST->value,
            VoiceActingSeederEnum::DREAM_CAST->value,
        ];

        VoiceActing::upsert($this->generateNamesArray($voiceActing), 'name');
    }
}
