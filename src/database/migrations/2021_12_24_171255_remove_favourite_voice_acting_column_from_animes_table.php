<?php

declare(strict_types=1);

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
        Schema::table('animes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('favourite_voice_acting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->foreignUuid('favourite_voice_acting')
                  ->nullable()
                  ->constrained('voice_acting')
                  ->nullOnDelete();
        });
    }
};
