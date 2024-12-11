<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const array STATUSES = ['Анонс', 'Онгоинг', 'Вышел'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            // Values are hardcoded here so that migration would not fail if enum will be deleted.
            $table->enum('status', self::STATUSES)->after('title')->default('Анонс')->index();
            $table->float('rating', precision: 24)->after('status')->default(0.0);
            $table->unsignedInteger('episodes')->after('rating')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn(['status', 'rating', 'episodes']);
        });
    }
};
