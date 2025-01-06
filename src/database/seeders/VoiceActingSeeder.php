<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VoiceActingEnum;
use App\Models\VoiceActing;
use Illuminate\Database\Seeder;

class VoiceActingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voiceActing = array_map(static fn(string $voiceActing) => ['name' => $voiceActing], VoiceActingEnum::values());

        VoiceActing::query()->upsert($voiceActing, ['name']);
    }
}
