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
        Schema::table(
            'telegram_users',
            function (Blueprint $table) {
                $table->dropColumn(['nickname', 'username']);
            }
        );

        Schema::table(
            'telegram_users',
            function (Blueprint $table) {
                $table->string('first_name')->after('telegram_id')->nullable();
                $table->string('last_name')->after('first_name')->nullable();
                $table->string('username')->after('last_name')->nullable();
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
            'telegram_users',
            function (Blueprint $table) {
                $table->addColumn('string', 'nickname');
                $table->addColumn('string', 'username');
            }
        );
    }
};
