<?php

namespace Database\Seeders;

use App\Enums\TagSeederEnum;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::insert([
            [
                'id' => Str::uuid(),
                'name' => TagSeederEnum::ADMIN_TAG->value,
            ],
            [
                'id' => Str::uuid(),
                'name' => TagSeederEnum::FIRST_MODERATOR_TAG->value,
            ],
            [
                'id' => Str::uuid(),
                'name' => TagSeederEnum::SECOND_MODERATOR_TAG->value,
            ],
        ]);
    }
}
