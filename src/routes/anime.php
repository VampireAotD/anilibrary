<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;
use App\Http\Controllers\Anime\RandomAnimeController;

Route::get('/anime/random', RandomAnimeController::class)->name('anime.random');
Route::resource('anime', AnimeController::class)->except(['edit', 'destroy']);
