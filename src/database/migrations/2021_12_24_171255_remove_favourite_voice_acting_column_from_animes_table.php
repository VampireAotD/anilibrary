<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveFavouriteVoiceActingColumnFromAnimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table(
                'animes',
                function (Blueprint $table) {
                    $table->dropConstrainedForeignId('favourite_voice_acting');
                }
            );
        }

        Schema::table(
            'animes',
            function (Blueprint $table) {
                $table->dropColumn('favourite_voice_acting');
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
        Schema::table(
            'animes',
            function (Blueprint $table) {
                $table->foreignUuid('favourite_voice_acting')
                      ->nullable()
                      ->constrained('voice_acting')
                      ->nullOnDelete();
            }
        );
    }
}
