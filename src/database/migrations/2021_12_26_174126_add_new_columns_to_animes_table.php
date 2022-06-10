<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AnimeStatusEnum;

class AddNewColumnsToAnimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->enum('status', AnimeStatusEnum::values());
            $table->float('rating')->default(1);
            $table->string('episodes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('rating');
            $table->dropColumn('episodes');
        });
    }
}
