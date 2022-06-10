<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Class TmpFolderSeeder
 * @package Database\Seeders
 */
class TmpFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (!File::exists(storage_path('tmp'))) {
            File::makeDirectory(storage_path('tmp'));

            if (!File::exists(storage_path('tmp/.gitignore'))) {
                copy(storage_path('logs/.gitignore'), storage_path('tmp/.gitignore'));
            }
        }
    }
}
