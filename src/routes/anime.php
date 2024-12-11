<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;
use App\Http\Controllers\Anime\RandomAnimeController;
use App\Http\Controllers\UserAnimeList\UserAnimeListController;

Route::get('/anime/random', RandomAnimeController::class)->name('anime.random');
Route::resource('anime', AnimeController::class)->except(['edit', 'destroy']);

Route::apiResource('anime-list', UserAnimeListController::class)->except('show')->parameter('anime-list', 'anime');
