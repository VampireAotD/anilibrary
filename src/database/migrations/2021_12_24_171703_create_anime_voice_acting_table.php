<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimeVoiceActingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'anime_voice_acting',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('anime_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('voice_acting_id')->constrained('voice_acting')
                      ->cascadeOnDelete();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anime_voice_acting');
    }
}
