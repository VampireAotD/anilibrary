<?php

declare(strict_types=1);

use App\Enums\AnimeStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'animes',
            function (Blueprint $table) {
                $table->enum('status', AnimeStatusEnum::values())->default(AnimeStatusEnum::ANNOUNCE->value);
                $table->float('rating')->default(1);
                $table->string('episodes')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'animes',
            function (Blueprint $table) {
                $table->dropColumn(['status', 'rating', 'episodes']);
            }
        );
    }
};
