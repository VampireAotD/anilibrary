<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAliasColumnToImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table(
                'images',
                function (Blueprint $table) {
                    $table->string('alias')->after('path');
                }
            );

            return;
        }

        Schema::table(
            'images',
            function (Blueprint $table) {
                $table->string('alias')->after('path')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'images',
            function (Blueprint $table) {
                $table->dropColumn(['alias']);
            }
        );
    }
}
