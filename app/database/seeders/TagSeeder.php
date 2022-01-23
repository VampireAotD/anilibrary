<?php

namespace Database\Seeders;

use App\Enums\TagSeederEnum;
use App\Models\Tag;
use App\Services\Traits\CanPrepareDataForBatchInsert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Class TagSeeder
 * @package Database\Seeders
 */
class TagSeeder extends Seeder
{
    use CanPrepareDataForBatchInsert;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => TagSeederEnum::ADMIN_TAG->value,
            ],
            [
                'name' => TagSeederEnum::FIRST_MODERATOR_TAG->value,
            ],
            [
                'name' => TagSeederEnum::SECOND_MODERATOR_TAG->value,
            ],
        ];

        Tag::insert($this->prepareArrayForInsert($tags));
    }
}
