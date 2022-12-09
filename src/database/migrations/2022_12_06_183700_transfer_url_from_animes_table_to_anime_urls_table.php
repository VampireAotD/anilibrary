<?php

declare(strict_types=1);

use App\Models\Anime;
use App\Models\AnimeUrl;
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
        Anime::query()->select(['id', 'url'])->each(
            function (Anime $anime) {
                $anime->urls()->save(new AnimeUrl(['anime_id' => $anime->id, 'url' => $anime->url]));
            }
        );


        Schema::table(
            'animes',
            function (Blueprint $table) {
                $table->dropColumn('url');
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
            'animes',
            function (Blueprint $table) {
                $table->string('url')->after('title');
            }
        );

        AnimeUrl::query()->select(['anime_id', 'url'])->each(
            function (AnimeUrl $animeUrl) {
                Anime::query()->where('id', $animeUrl->anime_id)->update(['url' => $animeUrl->url]);
            }
        );

        AnimeUrl::truncate();
    }
};
