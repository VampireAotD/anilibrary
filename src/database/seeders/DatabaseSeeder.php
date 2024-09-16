<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws Throwable
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                VoiceActingSeeder::class,
                RoleSeeder::class,
            ]);
        });
    }
}
