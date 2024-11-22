<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;

Route::resource('anime', AnimeController::class)->except(['edit', 'destroy']);
