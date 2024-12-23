<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;
use App\Http\Controllers\Anime\RandomAnimeController;
use App\Http\Controllers\UserAnimeList\UserAnimeListController;
use App\Models\Anime;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

Route::get('/anime/random', RandomAnimeController::class)->name('anime.random');
Route::resource('anime', AnimeController::class)->except(['edit', 'destroy'])
     ->breadcrumbs(static function (ResourceBreadcrumbs $breadcrumbs) {
         $breadcrumbs->index('Anime', 'dashboard');
         $breadcrumbs->show(static fn(Anime $anime) => $anime->title);
     });

Route::apiResource('anime-list', UserAnimeListController::class)->except('show')->parameter('anime-list', 'anime');
