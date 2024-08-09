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
        Schema::create('anime_synonyms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('anime_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('synonym');
            $table->timestamps();

            $table->unique(['anime_id', 'synonym']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anime_synonyms');
    }
};
