<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TagSeederEnum;
use App\Models\Tag;
use App\Services\Traits\CanGenerateNamesArray;
use Illuminate\Database\Seeder;

/**
 * Class TagSeeder
 * @package Database\Seeders
 */
class TagSeeder extends Seeder
{
    use CanGenerateNamesArray;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tags = [
            TagSeederEnum::ADMIN_TAG->value,
            TagSeederEnum::FIRST_MODERATOR_TAG->value,
            TagSeederEnum::SECOND_MODERATOR_TAG->value,
        ];

        Tag::upsert($this->generateNamesArray($tags), 'name');
    }
}
