<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(
    function () {
        Route::resource('anime', AnimeController::class);
    }
);
