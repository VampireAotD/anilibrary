<?php

namespace Database\Seeders;

use App\Models\VoiceActing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoiceActingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoiceActing::insert([
            [
                'id' => Str::uuid(),
                'name' => 'AniDUB',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'AniLibria',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Студийная Банда',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'AnimeVost',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Dream Cast',
            ],
        ]);
    }
}
