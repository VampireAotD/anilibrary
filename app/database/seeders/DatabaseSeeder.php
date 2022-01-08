<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(VoiceActingSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(TmpFolderSeeder::class);
    }
}
