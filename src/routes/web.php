<?php

declare(strict_types=1);

use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::get(
            '/',
            function () {
                return Inertia::render('Dashboard');
            }
        )->name('dashboard');

        Route::group(
            ['name' => 'profile'],
            function () {
                Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
                Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            }
        );
    }
);

require __DIR__ . '/auth.php';
require __DIR__ . '/anime.php';
require __DIR__ . '/invitation.php';
