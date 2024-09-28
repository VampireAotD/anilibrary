<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            // Values are hardcoded here so that migration would not fail if enum will be deleted.
            $statuses = ['Анонс', 'Онгоинг', 'Вышел'];

            $table->enum('status', $statuses)->after('title')->default('Анонс')->index();
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
