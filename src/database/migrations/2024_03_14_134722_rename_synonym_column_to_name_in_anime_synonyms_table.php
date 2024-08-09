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
        Schema::table('anime_synonyms', function (Blueprint $table) {
            $table->renameColumn('synonym', 'name');
            $table->renameIndex('anime_synonyms_anime_id_synonym_unique', 'anime_synonyms_anime_id_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anime_synonyms', function (Blueprint $table) {
            $table->renameColumn('name', 'synonym');
            $table->renameIndex('anime_synonyms_anime_id_name_unique', 'anime_synonyms_anime_id_synonym_unique');
        });
    }
};
