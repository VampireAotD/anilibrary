<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TagEnum;
use App\Models\Tag;
use App\Traits\CanGenerateNamesArray;
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
        Tag::query()->upsert($this->generateNamesArray(TagEnum::values()), 'name');
    }
}
