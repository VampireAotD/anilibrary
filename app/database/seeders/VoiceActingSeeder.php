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
                'name' => 'AniDub',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Anilibria',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Studio Band',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'DreamCast',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Professional',
            ]
        ]);
    }
}
