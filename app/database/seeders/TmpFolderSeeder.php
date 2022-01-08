<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TmpFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::makeDirectory(storage_path('tmp'));

        copy(storage_path('logs/.gitignore'), storage_path('tmp/.gitignore'));
    }
}
