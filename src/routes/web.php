<?php

declare(strict_types=1);

use App\Http\Controllers\Anime\AnimeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['as' => 'profile.'], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::resource('anime', AnimeController::class)->only(['index', 'show', 'store', 'update']);

require __DIR__ . '/auth.php';
require __DIR__ . '/invitation.php';
