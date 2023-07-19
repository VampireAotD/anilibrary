<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VoiceActingEnum;
use App\Models\VoiceActing;
use App\Traits\CanTransformArray;
use Illuminate\Database\Seeder;

/**
 * Class VoiceActingSeeder
 * @package Database\Seeders
 */
class VoiceActingSeeder extends Seeder
{
    use CanTransformArray;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VoiceActing::query()->upsert($this->toAssociativeArrayWithUuid('name', VoiceActingEnum::values()), 'name');
    }
}
