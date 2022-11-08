<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimeGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'anime_genres',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('anime_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('genre_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('anime_genres');
    }
}
