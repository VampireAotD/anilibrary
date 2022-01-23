<?php

namespace Database\Seeders;

use App\Models\VoiceActing;
use App\Services\Traits\CanPrepareDataForBatchInsert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'name' => 'AniDUB',
            ],
            [
                'name' => 'AniLibria',
            ],
            [
                'name' => 'Студийная Банда',
            ],
            [
                'name' => 'AnimeVost',
            ],
            [
                'name' => 'Dream Cast',
            ],
        ];

        VoiceActing::insert($this->prepareArrayForInsert($voiceActing));
    }
}
