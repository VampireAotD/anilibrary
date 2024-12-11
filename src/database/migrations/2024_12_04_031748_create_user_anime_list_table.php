<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const array STATUSES = ['plan_to_watch', 'watching', 'on_hold', 'completed', 'dropped'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_anime_list', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('anime_id')->constrained()->cascadeOnDelete();
            // Values are hardcoded here so that migration would not fail if enum will be deleted.
            $table->enum('status', self::STATUSES)->default('plan_to_watch');
            $table->float('rating', precision: 24)->default(0.0);
            $table->unsignedInteger('episodes')->default(0);
            $table->timestamps();

            $table->primary(['user_id', 'anime_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_anime_list');
    }
};
