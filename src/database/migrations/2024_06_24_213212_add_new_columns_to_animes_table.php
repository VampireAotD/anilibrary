<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const array TYPES = ['ТВ Сериал', 'Фильм'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            // Values are hardcoded here so that migration would not fail if enum will be deleted.
            $table->enum('type', self::TYPES)->after('title')->index();
            $table->year('year')->after('episodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn(['type', 'year']);
        });
    }
};
