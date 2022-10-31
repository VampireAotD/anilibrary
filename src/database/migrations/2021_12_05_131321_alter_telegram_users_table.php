<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTelegramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'telegram_users',
            function (Blueprint $table) {
                $table->dropColumn(['nickname', 'username']);
            }
        );

        Schema::table(
            'telegram_users',
            function (Blueprint $table) {
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('username')->unique()->nullable();
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
            'telegram_users',
            function (Blueprint $table) {
                $table->addColumn('string', 'nickname');
                $table->addColumn('string', 'username')->unique();
            }
        );
    }
}
