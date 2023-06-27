<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table(
                'users',
                function (Blueprint $table) {
                    $table->dropColumn(['id', 'name']);
                }
            );

            Schema::table(
                'users',
                function (Blueprint $table) {
                    $table->uuid('id')->first()->primary();
                    $table->foreignUuid('telegram_user_id')->after('id')->constrained()->cascadeOnDelete();
                    $table->string('name')->after('email')->nullable();
                }
            );

            return;
        }

        Schema::dropIfExists('users');

        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('telegram_user_id')->constrained()->cascadeOnDelete();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
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
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
            }
        );
    }
};
